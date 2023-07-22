<?php

namespace App\Controller\API\V1;

use App\Config\SolutionStatus;
use App\DTO\UserSolutionsRequest;
use App\DTO\UserSolutionsStatusRequest;
use App\Repository\UserSolutionRepository;
use App\Services\Serializer\DTOSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/solution", name: "solution_api")]
class SolutionApiController extends AbstractController {

    #[Route("/{userId<\d+>}/{taskId<\d+>}", name: "_get_solutions")]
    public  function getSolutions(int $userId, int $taskId, UserSolutionRepository $userSolutionRepository, DTOSerializer $DTOSerializer): JsonResponse {

        // get user solutions
        $userSolution = $userSolutionRepository->findBy([
            "user" => $userId,
            "task" => $taskId,
         ], [
            "uploadedAt" => "desc",
        ], 10);

        if (!$userSolution) {
            return $this->json(new UserSolutionsStatusRequest(
                taskId: 0,
                solutionId: 0,
                solutionStatus: SolutionStatus::NOT_FOUND
            ), 404);
        }

        $solutions = new UserSolutionsRequest($userSolution);

        // serialize response
        $response = $DTOSerializer->normalize($solutions, "json");

        return $this->json($response);
    }

}