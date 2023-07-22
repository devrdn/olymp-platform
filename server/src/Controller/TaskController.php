<?php

namespace App\Controller;

use App\Constants\MessageConstants;
use App\Entity\TaskTest;
use App\Entity\Task;
use App\Entity\TaskMeta;
use App\Entity\User;
use App\Entity\UserSolution;
use App\Exception\FileUploaderException;
use App\Exception\TestUploaderException;
use App\Form\TaskType;
use App\Form\TaskTestType;
use App\Form\UploadSolutionType;
use App\Repository\TaskRepository;
use App\Repository\TaskMetaRepository;
use App\Repository\UserSolutionRepository;
use App\Services\TestUploader;
use App\Services\SolutionUploader;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task/{page<\d+>}', methods: ['GET'], name: 'app_task_list', defaults: ['page' => 0])]
    public function list(int $page, TaskRepository $taskRepository): Response
    {
        $offset = max(0, $page);
        $paginator = $taskRepository->getPaginator($offset);

        return $this->render('task/list.html.twig', [
            'tasks' => $paginator,
            'previous' => $offset - TaskRepository::_TASKS_PER_PAGE,
            'next' => min(
                count($paginator),
                $offset + TaskRepository::_TASKS_PER_PAGE
            ),
        ]);
    }


    #[Route('/task/view/{id<\d+>}', methods: ['GET'], name: 'app_task_view')]
    public function view(int $id, TaskRepository $taskRepository): Response
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

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/task/view/{id<\d+>}', name: 'app_solution_upload', methods: ['POST'])]
    public function uploadSolution(
        int                    $id,
        Request                $request,
        SolutionUploader       $solutionUploader,
        TaskRepository         $taskRepository,
        UserSolutionRepository $userSolutionRepository,
    ): Response
    {
        $task = $taskRepository->find($id);
        if (!$task) {
            throw $this->createNotFoundException(
                'Task with ID: ' . $id . ' not found'
            );
        }

        $uploadSolutionForm = $this->createForm(UploadSolutionType::class);
        $uploadSolutionForm->handleRequest($request);

        // check is form is valid and submitted
        if (!($uploadSolutionForm->isSubmitted() && $uploadSolutionForm->isValid())) {
            return $this->renderForm('task/index.html.twig', [
                'task' => $task,
                'form' => $uploadSolutionForm,
            ]);
        }

        /** @var User $user */
        $user = $this->getUser();

        /** @var UploadedFile $uploadedSolutionAsFile */
        $uploadedSolutionAsFile = $uploadSolutionForm->get('file_solution')->getData();
        $uploadedSolutionAsText = $uploadSolutionForm->get('text_solution')->getData();
        $solutionExtension = $uploadSolutionForm->get('language')->getData();

        // generate target directory
        $targetDirectory = $this->getParameter('user_directory') . '/' . $user->getId();
        $fileName = '';

        // Upload user solution
        try {
            // Handle File User Solution
            if ($uploadedSolutionAsFile) {
                $fileName = $solutionUploader->uploadSolutionAsFile($uploadedSolutionAsFile, $id, $targetDirectory);
            } // Handle Text User Solution
            else if ($uploadedSolutionAsText) {
                $fileName = $solutionUploader->uploadSolutionAsText($uploadedSolutionAsText, $id, $targetDirectory, $solutionExtension);
            }
        } catch (FileUploaderException $exception) {
            $this->addFlash('error', "There are some errors during uploading the solution");
            return $this->redirectToRoute('app_task_view', ['id' => $id]);
        }

        // Save to DataBase
        $userSolution = new UserSolution($user, $task, $fileName);
        $userSolutionRepository->save($userSolution, true);

        $this->addFlash('success', MessageConstants::SUCCESSFULLY_UPLOADED);

        return $this->redirectToRoute('app_task_view', ['id' => $id]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/task/addtest/{id<\d+>}', methods: ['POST', 'GET'], name: 'app_task_add_test')]
    public function addTest(int $id, Request $request, 
        TaskRepository $taskRepository, TestUploader $testUploader): Response
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
                'task' => $task
            ]);
        }

        // if form is submitted and valid

        /** @var TaskTest $taskTest */
        $taskTest = $taskForm->getData();
        $archive = $taskForm->get('tests')->getData();
        $inputPattern = $taskForm->get('input_pattern')->getData();
        $outputPattern = $taskForm->get('output_pattern')->getData();

        // if has no uploaded tests
        $testUploader->openZip($archive);
        $numberOfUploadedTests = 0;

        try {
            $numberOfUploadedTests = $testUploader->extractTestsIfHasPair($task, $inputPattern, $outputPattern, $this->getParameter('test_directory'));
        } catch (TestUploaderException $exception) {
            $this->addFlash('warning', MessageConstants::UNSUCCESSFUL_UPLOAD);
            return $this->redirectToRoute('app_task_add_test', ['id' => $task->getId()]);
        }

        $this->addFlash('success', "{$numberOfUploadedTests} tests was uploaded to task '{$task->getName()}'");
        return $this->redirectToRoute('app_task_add_test', ['id' => $task->getId()]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/task/create', methods: ['GET', 'POST'], name: 'app_task_create')]
    public function create(Request $request, TaskRepository $taskRepository, TaskMetaRepository $taskMetaRepository): Response
    {
        $task = new Task();

        // Creating Form and Handle user request
        $taskForm = $this->createForm(TaskType::class, $task);
        $taskForm->handleRequest($request);

        // Handle and Save Form
        if ($taskForm->isSubmitted()  && $taskForm->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            /** @var Task $task */
            $task = $taskForm->getData();
            $task->setPublished(0);
            $taskMeta = new TaskMeta();
            $taskMeta->setAuthor($user->getId()); // temporary
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/task/update/{id<\d{1,5}>}', methods: ['GET', 'POST'], name: 'app_task_update')]
    public function update(int $id, Request $request, TaskRepository $taskRepository): Response
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
        if ($taskForm->isSubmitted() && $taskForm->isValid()) {

            /** @var Task $task */
            $task = $taskForm->getData();

            // todo: change as separate route /task/setPublished
            if ($isPublished && $task->isPublished() === $isPublished) {
                return $this->redirectToRoute('app_task_update', ['id' => $id]);
            }

            $taskRepository->save($task, true);

            // todo: Maybe Create FlaskGenerator Service 
            $this->addFlash('success', "Task `{$task->getName()}` was successfully updated.");

            return $this->redirectToRoute('app_task_view', ['id' => $id]);
        }
 

        return $this->renderForm('task/form.html.twig', [
            'task' => $task,
            'form' => $taskForm,
        ]);
    }
}
