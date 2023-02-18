<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskMeta;
use App\Entity\User;
use App\Exception\FileUploaderException;
use App\Form\TaskType;
use App\Form\UploadSolutionType;
use App\Repository\TaskMetaRepository;
use App\Repository\TaskRepository;
use App\Services\FileUploader;
use DateTimeImmutable;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class TaskController extends AbstractController
{
    #[Route('/task/{page<\d+>}', methods: ['GET'], name: 'app_task_list',  defaults: ['page' => 0])]
    public function showAllTasks(int $page, TaskRepository $taskRepository): Response
    {
        $offset = max(0, $page);
        $paginator = $taskRepository->getTaskPaginator($offset);

        return $this->render('task/list.html.twig', [
            'tasks' => $paginator,
            'previous' => $offset - TaskRepository::PAGINATOR_PER_PAGE,
            'next' => min(
                count($paginator),
                $offset + TaskRepository::PAGINATOR_PER_PAGE
            ),
        ]);
    }


    #[Route('/task/view/{id<\d+>}', methods: ['GET'], name: 'app_task_single_page')]
    public function showTask(int $id, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'Task with ID: ' . $id . ' not found'
            );
        }

        $uploadSolutionForm = $this->createForm(UploadSolutionType::class);

        return $this->renderForm('task/index.html.twig', [
            'task' => $task,
            'form' => $uploadSolutionForm,
        ]);
    }

    #[Route('/task/upload/{id<\d+>}', methods: ['POST'], name: 'app_solution_upload')]
    public function uploadTask(int $id, Request $request, FileUploader $fileUploader)
    {
        $uploadSolutionForm = $this->createForm(UploadSolutionType::class);
        $uploadSolutionForm->handleRequest($request);

        if (!($uploadSolutionForm->isSubmitted() && $uploadSolutionForm->isValid())) {
            return $this->redirectToRoute('app_task_single_page', ['id' => $id]);
        }

        // if form is submitted and valid
        // todo: make as entity
        // todo: maybe another method

        /** @var User $user */
        $user = $this->getUser();

        /** @var UploadedFile $uploadedSolutionAsFile */
        $uploadedSolutionAsFile = $uploadSolutionForm->get('file_solution')->getData();
        $uploadedSolutionAsText = $uploadSolutionForm->get('text_solution')->getData();

        $fileUploader->setTargetDirectory($this->getParameter('user_directory') . '/' . $user->getId());

        // Handle File User Solution
        if ($uploadedSolutionAsFile) {
            // check if user file extension matches selected extension
            $fileExtension = $uploadedSolutionAsFile->getClientOriginalExtension();

            // create new file name task_{task_id}_{data}.{ext}
            $newFileName = FileUploader::createFileUniqueNameByDate("task", $id, $fileExtension);

            // upload user solution to folder
            try {
                $fileUploader->uploadFile($uploadedSolutionAsFile, $newFileName);
            } catch (FileUploaderException $exception) {
                $this->addFlash('error', $exception->getMessage());
                return $this->redirectToRoute('app_task_single_page', ['id' => $id]);
            }

            // add flash message and redirect  if solution is succefully uploaded
            $this->addFlash('success', 'Solution is succefully uploaded');
            return $this->redirectToRoute('app_task_single_page', ['id' => $id]);
        }

        // Handle Text User Solution
        if ($uploadedSolutionAsText) {
            $solutionExtesion = $uploadSolutionForm->get('language')->getData();

            // create new file name task_{task_id}_{data}.{ext}
            $newFileName = FileUploader::createFileUniqueNameByDate("task", $id, $solutionExtesion->value);

            // upload user solution to folder
            try {
                $fileUploader->createAndUploadFile($uploadedSolutionAsText, $newFileName);
            } catch (FileUploaderException $exception) {
                $this->addFlash('error', $exception->getMessage());
                return $this->redirectToRoute('app_task_single_page', ['id' => $id]);
            }

            // add flash message and redirect if solution is succefully uploaded
            $this->addFlash('success', 'Solution is succefully uploaded');
            return $this->redirectToRoute('app_task_single_page', ['id' => $id]);
        }

        return $this->redirectToRoute('app_task_single_page', ['id' => $id]);
    }


    #[Route('/task/create', methods: ['GET', 'POST'], name: 'app_task_create')]
    public function createTask(Request $request, TaskRepository $taskRepository, TaskMetaRepository $taskMetaRepository): Response
    {
        $task = new Task();

        // Creating Form and Handle user request
        $taskForm = $this->createForm(TaskType::class, $task);
        $taskForm->handleRequest($request);

        // Handle and Save Form
        if ($taskForm->isSubmitted()  && $taskForm->isValid()) {
            /** @var Task $task */
            $task = $taskForm->getData();
            $task->setPublished(0);
            $taskMeta = new TaskMeta();
            $taskMeta->setAuthor('Nick'); // temporary
            $taskMeta->setTask($task);
            $taskMeta->setSolved(0);
            $taskMeta->setComplexity(0);
            $taskMeta->setCreatedAt(new DateTimeImmutable());
            $taskRepository->save($task, true);
            $taskMetaRepository->save($taskMeta, true);

            # TODO: Create FlaskGenerator Service 
            $this->addFlash('success', "Task `{$task->getName()}` was successfully saved.");

            return $this->redirectToRoute('app_task_list');
        }

        return $this->renderForm('task/form.html.twig', [
            'task' => $task,
            'form' => $taskForm,
        ]);
    }

    #[Route('/task/update/{id<\d{1,5}>}', methods: ['GET', 'POST'], name: 'app_task_update')]
    public function updateTask(int $id, Request $request, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);

        // check if task is exists
        // todo: change throw to addflash
        if (!$task) {
            throw $this->createNotFoundException(
                'Task with ID: ' . $id . ' not found'
            );
        }

        $isPublished = $task->isPublished();

        // Creating Form with method POST and handle user request
        $taskForm = $this->createForm(TaskType::class, $task, ['method' => 'POST']);
        $taskForm->handleRequest($request);

        // Handle and Save Form
        if ($taskForm->isSubmitted()  && $taskForm->isValid()) {

            /** @var Task $task */
            $task = $taskForm->getData();

            // todo: change as separate route /task/setPublished
            if ($isPublished && $task->isPublished() === $isPublished) {
                return $this->redirectToRoute('app_task_update', ['id' => $id]);
            }

            $taskRepository->save($task, true);

            // todo: Maybe Create FlaskGenerator Service 
            $this->addFlash('success', "Task `{$task->getName()}` was successfully updated.");

            return $this->redirectToRoute('app_task_single', ['id' => $id]);
        }


        return $this->renderForm('task/form.html.twig', [
            'task' => $task,
            'form' => $taskForm,
        ]);
    }
}
