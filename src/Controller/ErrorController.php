<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller de la page 404
 */
class ErrorController extends AbstractController
{
    /**
     * Affiche la page 404 lorsque l'URL demandée est introuvable
     * 
     * @param string $url URL qui a généré l'erreur
     * 
     * @return Response
     */
    public function notFound(string $url): Response
    {
        return $this->render('error/404.html.twig', [
            'url' => $url
        ]);
    }
}
