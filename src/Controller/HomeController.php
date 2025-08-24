<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\JourneyRepository;

/**
 * Controller de la page d'accueil
 */
final class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil avec la liste des prochains trajets disponibles
     * 
     * @param JourneyRepository $journeyRepository
     * 
     * @return Response
     */
    public function index(JourneyRepository $journeyRepository): Response
    {
        $journeys = $journeyRepository->findUpComingAvailableJourneys();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'journeys' => $journeys,
        ]);
    }
}