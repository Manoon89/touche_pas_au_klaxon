<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AgencyController extends AbstractController
{
    #[Route('/agency', name: 'app_agency')]
    public function index(): Response
    {
        return $this->render('agency/index.html.twig', [
            'controller_name' => 'AgencyController',
        ]);
    }
}
