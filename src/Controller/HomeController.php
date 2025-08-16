<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\JourneyRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JourneyRepository $journeyRepository): Response
    {
        $journeys = $journeyRepository->createQueryBuilder('j')
            ->where('j.departureDate >= :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('j.departureDate', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'journeys' => $journeys,
        ]);
    }
}