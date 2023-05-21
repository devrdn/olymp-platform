<?php

namespace App\Controller\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/logout', name: 'app_logout', methods: ["GET"])]
    public function logout(): Response
    {
        // controller can't be empty, so ...
        // return new response!
        return new Response("You are cool, bro!");
    }
}
