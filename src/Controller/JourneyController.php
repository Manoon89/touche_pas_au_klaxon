<?php

namespace App\Controller;

use App\Entity\Journey;
use App\Repository\JourneyRepository;
use App\Form\JourneyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/journey')]
final class JourneyController extends AbstractController
{

    #[Route('/new', name: 'journey_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $journey = new Journey();

        $journey->setUser($this->getUser());

        $form = $this->createForm(JourneyType::class, $journey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($journey);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journey/new.html.twig', [
            'journey' => $journey,
            'form' => $form,
        ]);
    }

    #[Route('/{journeyId<\d+>}', name: 'journey_show', methods: ['GET'])]
    public function show(int $journeyId, JourneyRepository $journeyRepository): Response
    {
        $journey = $journeyRepository->findWithAgencies($journeyId);
    
        if (!$journey) {
            throw $this->createNotFoundException('Trajet introuvable');
        }
    
        return $this->render('journey/show.html.twig', [
            'journey' => $journey,
        ]);
    }
    #[Route('/{journeyId}/edit', name: 'journey_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $journeyId, JourneyRepository $journeyRepository, EntityManagerInterface $entityManager): Response
    {
        $journey = $journeyRepository->findWithAgencies($journeyId);

        $form = $this->createForm(JourneyType::class, $journey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journey/edit.html.twig', [
            'journey' => $journey,
            'form' => $form,
        ]);
    }

    #[Route('/{journeyId}/delete', name: 'journey_delete', methods: ['POST'])]
    public function delete(Request $request, int $journeyId, JourneyRepository $journeyRepository, EntityManagerInterface $entityManager): Response
    {
        $journey = $journeyRepository->findWithAgencies($journeyId);

        if ($this->isCsrfTokenValid('delete'.$journey->getId(), $request->request->get('_token'))) {
            $entityManager->remove($journey);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
