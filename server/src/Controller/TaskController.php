<?php

namespace App\Controller;

use App\Entity\TaskTest;
use App\Form\TaskTestType;
use App\Repository\TaskRepository;
use App\Repository\TaskTestRepository;
use App\Services\ZipManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task/{page<\d+>}', methods: ['GET'], name: 'app_task_list',  defaults: ['page' => 0])]
    public function showAllTasks(int $page, TaskRepository $taskRepository): Response
    {
        $offset = max(0, $page);
        $paginator = $taskRepository->getTaskPaginator($offset);
        //$tasks = $taskRepository->findAll();

        return $this->render('task/list.html.twig', [
            'tasks'     => $paginator,
            'previous'  => $offset - TaskRepository::PAGINATOR_PER_PAGE,
            'next'      => min(count($paginator), $offset + TaskRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    #[Route('/task/view/{id<\d+>}', methods: ['GET'], name: 'app_task_single_page')]
    public function showTask(int $id,  TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'Task with ID: ' . $id . ' not found'
            );
        }

        return $this->render('task/index.html.twig', ['task' => $task]);
    }

    #[Route('/task/addtest/{id<\d+>}', methods: ['POST', 'GET'], name: 'app_task_add_test')]
    public function addTest(int $id, Request $request, TaskRepository $taskRepository, TaskTestRepository $taskTestRepository, ZipManager $zipManager): Response
    {
        // find task with this id
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'Task with ID: ' . $id . ' not found'
            );
        }

        $taskTest = new TaskTest();
        $taskTest->setTask($task);

        // create and handle task test form
        $taskForm = $this->createForm(TaskTestType::class, $taskTest);
        $taskForm->handleRequest($request);


        // if form is not submitted and valid
        if (!($taskForm->isSubmitted() && $taskForm->isValid())) {
            return $this->renderForm('task/add_task_form.html.twig', [
                'task' => $task,
                'form' => $taskForm,
            ]);
        }

        // if form is submitted and valid

        /** 
         * @var TaskTest $taskTest 
         */
        $taskTest = $taskForm->getData();
        $archive = $taskForm->get('tests')->getData();
        $input = $taskForm->get('input_pattern')->getData();
        $output =  $taskForm->get('output_pattern')->getData();

        // if has no uploaded tests
        if (!$archive) {
            $taskTestRepository->save($taskTest, true);
            return $this->redirectToRoute('app_task_list');
        }

        // todo: add flash messages and refactor some code with error Messages and service

        if (($err = $zipManager->isAllFilesCorrect($archive, $input, $output, "/\[id\]/", !empty($output)) !== TRUE)) {
            // $this->addFlash("error", $err);
            return $this->redirectToRoute('app_task_list');
        }

        $testDirectoryName = $this->getOutputDir($taskTest);

        // extract files in directory
        $zipManager->extractTo($testDirectoryName);
        //$zip->extractTo($testDirectoryName);
        //$taskTest->setInputData($testDirectoryName);

        return $this->redirectToRoute('app_task_list');
    }

    private function getOutputDir(TaskTest $taskTest): string|Response
    {
        $fileSystem = new Filesystem();
        $outputDir = $this->getParameter('test_directory') . '/' . $taskTest->getTask()->getId() . '/tests';

        // create new task directory for tests
        if (!$fileSystem->exists($outputDir)) {
            try {
                $fileSystem->mkdir($outputDir);
            } catch (IOException) {
                //$this->addFlash('danger', 'Cannot create test directory');
                return $this->redirectToRoute('app_task_list');
            }
        }

        return $outputDir;
    }
}
