<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller responsible for user login
 */
class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ["POST", "GET"])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get login error
        $error = $authenticationUtils->getLastAuthenticationError();

        // get last email
        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_email' => $lastEmail,
            'auth_error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ["POST", "GET"])]
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        return new Response("Logout");
    }
}
