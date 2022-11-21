<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private const OFFSET  = 20;

    #[Route('/task/{page}', methods: ['GET'], name: 'app_task_list',  defaults: ['page' => 0])]
    public function showAllTasks(int $page = 0, TaskRepository $taskRepository): Response
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

    #[Route('/task/view/{id}', methods: ['GET'], name: 'app_task_single_page')]
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
}
