<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function notFound(string $url): Response
    {
        return $this->render('error/404.html.twig', [
            'url' => $url
        ]);
    }
}
