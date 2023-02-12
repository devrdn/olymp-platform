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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ["POST", "GET"])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    { 
        // get login error
        $error = $authenticationUtils->getLastAuthenticationError();

        // get last email
        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_email' => $lastEmail,
            'auth_error' => $error,
        ]);
    }

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
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_task_list');
        }

        // render registration form
        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ["GET"])]
    public function logout(): Response
    {
        // controller can't be empty, so ...
        // return new response!
        return new Response("You are cool, bro!");
    }
}
