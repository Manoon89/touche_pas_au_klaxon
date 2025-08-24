<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Form\AgencyType;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Validator\Constraints\AgencyDeletion;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Controller de gestion des agences
 */
final class AgencyController extends AbstractController
{
    /**
     * Liste toutes les agences par ordre alphabétique de la ville
     * 
     * @param AgencyRepository $agencyRepository
     * 
     * @return Response
     */
    public function index(AgencyRepository $agencyRepository): Response
    {
        $agencies = $agencyRepository->createQueryBuilder('a')
            ->orderBy('a.city', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('agency/index.html.twig', [
            'controller_name' => 'AgencyController',
            'agencies' => $agencies,
        ]);
    }

    /**
     * Crée une nouvelle agence
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response Retourne le formulaire de création ou la redirection après soumission
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agency);
            $entityManager->flush();

            $this->addFlash('success', 'L\'agence a bien été créée');

            return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agency/new.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    /**
     * Modifie une agence
     * 
     * @param Request $request
     * @param int $agencyId
     * @param AgencyRepository $agencyRepository
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response
     */
    public function edit(Request $request, int $agencyId, AgencyRepository $agencyRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $agency = $agencyRepository->find($agencyId);

        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // On va vérifier via le validator que la ville n'est pas déjà utilisée pour un trajet
            $errors = $validator->validate($agency);
    
            if (count($errors) > 0 && $errors[0] !== null) {
                // On affiche le message d'erreur dans un flash
                $this->addFlash('error', $errors[0]->getMessage());
                return $this->redirectToRoute('agency_index');

            } elseif ($form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'L\'agence a bien été modifiée');
                
                return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprime une agence
     * 
     * @param Request $request
     * @param int $agencyId
     * @param AgencyRepository $agencyRepository
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response
     */
    public function delete(Request $request, int $agencyId, AgencyRepository $agencyRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $agency = $agencyRepository->find($agencyId);

        // On va vérifier via le validator que la ville n'est pas déjà utilisée pour un trajet
        $errors = $validator->validate($agency, new AgencyDeletion());

        if (count($errors) > 0 && $errors[0] !==null) {
            $this->addFlash('error', $errors[0]->getMessage());
            return $this->redirectToRoute('agency_index');
        }

        $token = $request->request->get('_token');
        if (!is_string($token)) {
            $token = null;
        }
        
        if ($agency instanceof Agency) {
            if ($this->isCsrfTokenValid('delete'.$agency->getId(), $token)) {
                $entityManager->remove($agency);
                $entityManager->flush();
            }
        }

        $this->addFlash('success', 'L\'agence a bien été supprimée');

        return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
    }
}