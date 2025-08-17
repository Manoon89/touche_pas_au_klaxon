<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Form\AgencyType;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/agency')]
final class AgencyController extends AbstractController
{
    #[Route(name: 'agency_index', methods: ['GET'])]
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

    #[Route('/new', name: 'agency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agency);
            $entityManager->flush();

            return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agency/new.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    #[Route('/{agencyId}/edit', name: 'agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $agencyId, AgencyRepository $agencyRepository, EntityManagerInterface $entityManager): Response
    {
        $agency = $agencyRepository->find($agencyId);

        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    #[Route('/{agencyId}', name: 'agency_delete', methods: ['POST'])]
    public function delete(Request $request, int $agencyId, AgencyRepository $agencyRepository, EntityManagerInterface $entityManager): Response
    {
        $agency = $agencyRepository->find($agencyId);

        if ($this->isCsrfTokenValid('delete'.$agency->getId(), $request->request->get('_token'))) {
            $entityManager->remove($agency);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
    }
}
