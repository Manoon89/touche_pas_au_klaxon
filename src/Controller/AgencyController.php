<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Form\AgencyType;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AgencyController extends AbstractController
{
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

    public function edit(Request $request, int $agencyId, AgencyRepository $agencyRepository, EntityManagerInterface $entityManager): Response
    {
        $agency = $agencyRepository->find($agencyId);

        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'agence a bien été modifiée');

            return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, int $agencyId, AgencyRepository $agencyRepository, EntityManagerInterface $entityManager): Response
    {
        $agency = $agencyRepository->find($agencyId);

        if ($this->isCsrfTokenValid('delete'.$agency->getId(), $request->request->get('_token'))) {
            $entityManager->remove($agency);
            $entityManager->flush();
        }

        $this->addFlash('success', 'L\'agence a bien été supprimée');

        return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
    }
}