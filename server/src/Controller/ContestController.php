<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \App\Repository\ContestRepository;

class ContestController extends AbstractController
{
    #[Route('/contests/{page<\d+>}', methods: ['GET'], name: 'app_contest_list', defaults: ['page' => 0])]
    public function showAllContests(int $page, ContestRepository $contestRepository): Response {
        $offset = max(0, $page);
        $paginator = $contestRepository->getPaginator($offset);

        return $this->render('contest/list.html.twig', [
            'contests' => $paginator,
            'previous' => $offset - ContestRepository::_CONTESTS_PER_PAGE,
            'next' => min(
                count($paginator),
                $offset + ContestRepository::_CONTESTS_PER_PAGE
            ),
        ]);
    }
}