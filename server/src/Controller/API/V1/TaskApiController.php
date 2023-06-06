<?php

namespace App\Controller\API\V1;

use App\Config\TaskStatus;
use App\DTO\TaskSolutions;
use App\DTO\TaskStatusRequest;
use App\Repository\UserSolutionRepository;
use App\Services\Serializer\DTOSerializer;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1", name: 'task_api')]
class TaskApiController extends AbstractController
{
    #[Route("/status/{user_id<\d+>}/{task_id<\d+>}", methods: ["GET"])]
    public function getStatus(int $user_id, int $task_id, UserSolutionRepository $userSolutionRepository, DTOSerializer $DTOSerializer): JsonResponse
    {
        $userSolution = $userSolutionRepository->findOneBy([
            "user" => $user_id,
            "task" => $task_id
        ], [
            "uploadedAt" => "desc",
        ]);

        if (!$userSolution) {
            return $this->json(new TaskStatusRequest(
                0, 0, TaskStatus::NOT_FOUND
            ), 404);
        }

        $taskStatus = new TaskStatusRequest(
            $userSolution->getId(),
            $userSolution->getTask()->getId(),
            $userSolution->getStatus()
        );

        $response = $DTOSerializer->normalize($taskStatus, "json");

        return $this->json($response);
    }

    #[Route('/tasks/{user_id<\d+>}/{task_id<\d+>}', methods: ["GET"])]
    public function getSolutions(int $user_id, int $task_id, UserSolutionRepository $userSolutionRepository, DTOSerializer $DTOSerializer): JsonResponse
    {
        $userSolution = $userSolutionRepository->findBy([
            "user" => $user_id,
            "task" => $task_id
        ], [
            "uploadedAt" => "desc",
        ]);

        if (!$userSolution) {
            return $this->json(new TaskStatusRequest(
                0, 0, TaskStatus::NOT_FOUND
            ), 404);
        }

        $data = new TaskSolutions($userSolution);

        //$data = new TaskSolutions($userSolution);
        $response = $DTOSerializer->normalize($data, "json");

        return $this->json($response);
    }
}