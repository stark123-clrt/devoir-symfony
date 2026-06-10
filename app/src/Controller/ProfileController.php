<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile_show')]
    public function show(NoteRepository $noteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $notes = $noteRepository->findBy(['owner' => $user], ['createdAt' => 'DESC']);

        return $this->render('profile/show.html.twig', compact('user', 'notes'));
    }

    #[Route('/profile/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // handle password change if provided
            $newPassword = $form->get('newPassword')->getData();
            if (!empty($newPassword)) {
                if (mb_strlen((string) $newPassword) < 6) {
                    $form->get('newPassword')->addError(new FormError('Le mot de passe doit contenir au moins 6 caractères.'));

                    return $this->render('profile/edit.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                // newPassword is a repeated type; value is the first value
                $hashed = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashed);
            }

            $em->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
