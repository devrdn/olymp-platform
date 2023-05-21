<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    // todo: add email verify and user ip
    #[Route('/register', name: 'app_register', methods: ["POST", "GET"])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = new User();

        // create form and handle user request
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // set created at date
            $user->setCreatedAt(new \DateTimeImmutable());

            $userRepository->save($user, true);
            
            return $this->redirectToRoute('app_login');
        }

        // render registration form
        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}