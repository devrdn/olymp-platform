<?php

namespace App\Controller;

use App\Entity\TaskTest;
use App\Exception\TestUploaderException;
use App\Form\TaskTestType;
use App\Repository\TaskRepository;
use App\Repository\TaskTestRepository;
use App\Services\TestUploader;
use App\Services\ZipService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function addTest(int $id, Request $request, TaskRepository $taskRepository, TestUploader $testUploader): Response
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
        $inputPattern = $taskForm->get('input_pattern')->getData();
        $outputPattern =  $taskForm->get('output_pattern')->getData();

        // if has no uploaded tests
        $testUploader->openZip($archive);

        try {
            $testUploader->extractTestsIfHasPair($task, $inputPattern, $outputPattern, $this->getParameter('test_directory'));
        } catch (TestUploaderException $exception) {
            # todo: add flash message
            throw new Exception($exception->getMessage());
            return $this->redirectToRoute('app_task_list');
        }

        return $this->redirectToRoute('app_task_list');
    }
}
