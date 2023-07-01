<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contest;
use App\Form\ContestType;
use App\Repository\ContestRepository;
use App\Entity\User;

class ContestController extends AbstractController
{
    #[Route('/contest/{page<\d+>}', methods: ['GET'], name: 'app_contest_list', defaults: ['page' => 0])]
    public function list(int $page, ContestRepository $contestRepository): Response {
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
    
    #[Route('/contest/view/{id<\d+>}', methods: ['GET'], name: 'app_contest_single_page')]
    public function view(int $id, ContestRepository $contestRepository): Response
    {
        $contest = $contestRepository->find($id);

        if (!$contest) {
            throw $this->createNotFoundException(
                'Contest with ID: ' . $id . ' not found'
            );
        }

        return $this->renderForm('contest/view.html.twig', [
            'contest' => $contest,
        ]);
    }
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/contest/create', methods: ['GET', 'POST'], name: 'app_contest_create')]
    public function create(Request $request, ContestRepository $contestRepository): Response
    {
        $contest = new Contest();

        // Creating Form and Handle user request
        $contestForm = $this->createForm(ContestType::class, $contest);
        $contestForm->handleRequest($request);

        // Handle and Save Form
        if ($contestForm->isSubmitted()  && $contestForm->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            /** @var Contest $contest */
            $contest = $contestForm->getData();
            $contest->setAuthor($user->getId());
            $contest->setCreatedAt(new \DateTimeImmutable());
            $contestRepository->save($contest, true);

            $this->addFlash('success', "Contest `{$contest->getName()}` was successfully saved.");

            return $this->redirectToRoute('app_contest_list');
        }

        return $this->renderForm('contest/create.html.twig', [
            'contest' => $contest,
            'form' => $contestForm,
        ]);
    }
}