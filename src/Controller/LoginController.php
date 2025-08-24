<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller pour la connexion et la déconnexion
 */
class LoginController extends AbstractController
{
    /**
     * Permet la connexion d'un utilisateur
     * 
     * @param AuthenticationUtils $authenticationUtils
     * 
     * @return Response Retourne la vue d'authentification
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'erreur d'authentification s'il en existe une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le nom de famille de l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Permet la déconnexion d'un utilisateur
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}