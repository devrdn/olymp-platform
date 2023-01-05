<?php

namespace App\Controller;

use App\Entity\TaskTest;
use App\Form\TaskTestType;
use App\Repository\TaskRepository;
use App\Repository\TaskTestRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

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
    public function addTest(int $id, Request $request, TaskRepository $taskRepository, TaskTestRepository $taskTestRepository, LoggerInterface $logger): Response
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

        if (!($taskForm->isSubmitted() && $taskForm->isValid())) {
            return $this->renderForm('task/add_task_form.html.twig', [
                'task' => $task,
                'form' => $taskForm,
            ]);
        }
        /** @var TaskTest $taskTest */
        $taskTest = $taskForm->getData();
        $archive = $taskForm->get('tests')->getData();

        // if user uploaded archive
        if (!$archive) {
            // save entity in db
            $taskTestRepository->save($taskTest, true);
            return $this->redirectToRoute('app_task_list');
        }

        $outputDir = $this->getOutDir($taskTest);
        
        $path = $this->getParameter('test_path') . '/' . $taskTest->getTask()->getId() . '/tests';
        $this->extractTests($archive, $outputDir);

        $taskTest->setInputData($path);
        $taskTestRepository->save($taskTest, true);

        return $this->redirectToRoute('app_task_list');
    }

    private function getOutDir(TaskTest $taskTest): string {
        $fileSystem = new Filesystem();
        $outputDir = $this->getParameter('test_directory') . '/' . $taskTest->getTask()->getId() . '/tests';
        
        // create new task directory for tests
        if (!$fileSystem->exists($outputDir)) {
            $fileSystem->mkdir($outputDir);
        }

        return $outputDir;
    }

    private function extractTests($archive, $outputPath)
    {
        $zip = new ZipArchive();

        try {
            // Open Zip Archive
            $zip->open($archive);

            // check if all files in archive is valid
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);
                if (preg_match("/(\d+)\_(input|output)\.txt/", $fileName, $matches)) {
                    $fileCorrectName = $matches[1] . '_' . ($matches[2] === 'input' ? 'output' : 'input') . '.txt';
                    $isValidFile = $zip->locateName($fileCorrectName);
                    if ($isValidFile === false) {
                        // todo: add flash message
                        //$this->addFlash('error', 'Files do not match structure *_input.txt, *_output.txt!');
                        throw new Exception('Files do not match structure *_input.txt, *_output.txt!');
                        break;
                    }
                } else {
                    throw new Exception('Files do not match structure *_input.txt, *_output.txt!');
                    break;
                }
            }

            // extract files in directory
            $zip->extractTo($outputPath);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
