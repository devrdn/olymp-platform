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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/solution", name: "solution_api")]
class SolutionApiController extends AbstractController {

    #[Route("/{taskId<\d+>}", name: "_get_solutions")]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public  function getSolutions(int $taskId,
                                  UserSolutionRepository $userSolutionRepository,
                                  DTOSerializer $DTOSerializer): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        // todo: name magic constant
        // get user solutions
        $userSolution = $userSolutionRepository->findBy([
            "user" => $user->getId(),
            "task" => $taskId,
         ], [
            "uploadedAt" => Criteria::DESC,
        ], 10);

        // serialize response
        $response = $DTOSerializer->normalize($userSolution, "json", ['groups' =>  ['user_solution']]);

        return $this->json($response);
    }
}