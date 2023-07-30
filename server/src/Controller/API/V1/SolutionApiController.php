<?php

namespace App\Controller\API\V1;

use App\Config\SolutionStatus;
use App\DTO\UserSolutionsRequest;
use App\DTO\UserSolutionsStatusRequest;
use App\Entity\User;
use App\Exception\AccessDeniedException;
use App\Repository\UserSolutionRepository;
use App\Services\Serializer\DTOSerializer;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/solution", name: "solution_api")]
class SolutionApiController extends AbstractController {

    #[Route("/{userId<\d+>}/{taskId<\d+>}", name: "_get_solutions")]
    public  function getSolutions(int $userId, int $taskId,
                                  UserSolutionRepository $userSolutionRepository,
                                  DTOSerializer $DTOSerializer): JsonResponse {

        /** @var User $user */
        $user = $this->getUser();
        if (!$user || $user->getId() != $userId) {
            return $this->json(new AccessDeniedException(), 403);
        }

        // get user solutions
        $userSolution = $userSolutionRepository->findBy([
            "user" => $userId,
            "task" => $taskId,
         ], [
            "uploadedAt" => Criteria::DESC,
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