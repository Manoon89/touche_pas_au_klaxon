<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Validator\Constraints\UserDeletion;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserController extends AbstractController
{
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->createQueryBuilder('u')
        ->orderBy('u.lastName', 'ASC')
        ->getQuery()
        ->getResult();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été créé');

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    public function edit(Request $request, int $userId, UserRepository $userRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($userId);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // On valide l'agence avec tous les constraints éventuels
            $errors = $validator->validate($user);
    
            if (count($errors) > 0) {
                // On affiche le premier message d'erreur dans un flash
                $this->addFlash('error', $errors[0]->getMessage());
                return $this->redirectToRoute('user_index');

            } elseif ($form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été modifié');
                
                return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, int $userId, UserRepository $userRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($userId);

        $errors = $validator->validate($user, new UserDeletion());

        if (count($errors) > 0) {
            $this->addFlash('error', $errors[0]->getMessage());
            return $this->redirectToRoute('user_index');
        }


        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')))  {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->addFlash('success', 'L\'utilisateur a bien été supprimé');

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}