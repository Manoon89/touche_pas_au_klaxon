<?php

namespace App\Controller;

use App\Entity\Journey;
use App\Entity\User;
use App\Repository\JourneyRepository;
use App\Form\JourneyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller pour la gestion des trajets
 */
final class JourneyController extends AbstractController
{
    /**
     * Crée un nouveau trajet lié à l'utilisateur connecté
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response Retourne la vue du formulaire de création ou la redirection après soumission
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $journey = new Journey();

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('Utilisateur non valide.');
        }
        
        // On récupère l'utilisateur pour pouvoir afficher ses informations dans le formulaire de création
        $journey->setUser($user);

        $form = $this->createForm(JourneyType::class, $journey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($journey);
            $entityManager->flush();

            $this->addFlash('success', 'Le trajet a bien été créé');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journey/new.html.twig', [
            'journey' => $journey,
            'form' => $form,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Permet d'afficher des informations complémentaires pour un trajet donné
     * 
     * @param int $journeyId
     * @param JourneyRepository $journeyRepository
     * 
     * @return Response
     */
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

    /**
     * Modifie les informations d'un trajet
     * 
     * @param Request $request
     * @param int $journeyId
     * @param JourneyRepository $journeyRepository
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response Retourne la vue du formulaire de modification ou la redirection après soumission
     */
    public function edit(Request $request, int $journeyId, JourneyRepository $journeyRepository, EntityManagerInterface $entityManager): Response
    {
        $journey = $journeyRepository->findWithAgencies($journeyId);

        $form = $this->createForm(JourneyType::class, $journey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le trajet a été modifié');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journey/edit.html.twig', [
            'journey' => $journey,
            'form' => $form,
        ]);
    }

    /**
     * Supprime un trajet
     * 
     * @param Request $request
     * @param int $journeyId
     * @param JourneyRepository $journeyRepository
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response Supprime le trajet et redirige vers la page d'accueil
     */
    public function delete(Request $request, int $journeyId, JourneyRepository $journeyRepository, EntityManagerInterface $entityManager): Response
    {
        $journey = $journeyRepository->findWithAgencies($journeyId);

        if ($journey === null) {
            $this->addFlash('error', 'Trajet introuvable');
            return $this->redirectToRoute('home');
        }

        $token = $request->request->get('_token');
        if (!is_string($token)) {
            $token = null;
        }
        
        if ($this->isCsrfTokenValid('delete'.$journey->getId(), $token)) {
            $entityManager->remove($journey);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le trajet a été supprimé');

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}