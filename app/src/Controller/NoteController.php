<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notes')]
class NoteController extends AbstractController
{
    #[Route('/', name: 'note_index', methods: ['GET'])]
    public function index(NoteRepository $noteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $notes = $noteRepository->findByOwner($this->getUser());

        return $this->render('note/index.html.twig', compact('notes'));
    }

    #[Route('/new', name: 'note_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($note->isPublic()) {
                $note->setNotePassword(null);
            } elseif (!$note->getNotePassword()) {
                $form->get('notePassword')->addError(new \Symfony\Component\Form\FormError('Le mot de passe est obligatoire pour une note privée.'));
                return $this->render('note/new.html.twig', compact('note', 'form'));
            }
            $note->setOwner($this->getUser());
            $em->persist($note);
            $em->flush();

            $this->addFlash('success', 'Note créée avec succès.');
            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/new.html.twig', compact('note', 'form'));
    }

    #[Route('/{id}/show', name: 'note_show', methods: ['GET', 'POST'])]
    public function show(Note $note, Request $request): Response
    {
        $user = $this->getUser();
        $isOwner = $user && $note->getOwner() === $user;
        $sessionKey = 'note_access_' . $note->getId();

        // Accès direct si propriétaire, note publique, ou accès déjà accordé en session
        if ($isOwner || $note->isPublic() || $request->getSession()->get($sessionKey)) {
            return $this->render('note/show.html.twig', [
                'note' => $note,
            ]);
        }

        // Note privée sans mot de passe défini : seul le propriétaire peut y accéder
        if (!$note->getNotePassword()) {
            throw $this->createAccessDeniedException('Cette note est privée et réservée à son auteur.');
        }

        // Note privée : demander le mot de passe
        $error = null;
        if ($request->isMethod('POST')) {
            $submitted = strtoupper(trim($request->request->get('note_password', '')));
            if ($submitted !== '' && $submitted === strtoupper($note->getNotePassword())) {
                $request->getSession()->set($sessionKey, true);
                return $this->redirectToRoute('note_show', ['id' => $note->getId()]);
            }
            $error = 'Mot de passe incorrect.';
        }

        return $this->render('note/password.html.twig', [
            'note'  => $note,
            'error' => $error,
        ]);
    }

    #[Route('/{id}/edit', name: 'note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Note $note, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($note->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($note->isPublic()) {
                $note->setNotePassword(null);
            } elseif (!$note->getNotePassword()) {
                $form->get('notePassword')->addError(new \Symfony\Component\Form\FormError('Le mot de passe est obligatoire pour une note privée.'));
                return $this->render('note/edit.html.twig', compact('note', 'form'));
            }
            $em->flush();

            $this->addFlash('success', 'Note modifiée avec succès.');
            return $this->redirectToRoute('note_show', ['id' => $note->getId()]);
        }

        return $this->render('note/edit.html.twig', compact('note', 'form'));
    }

    #[Route('/{id}/delete', name: 'note_delete', methods: ['POST'])]
    public function delete(Request $request, Note $note, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($note->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $note->getId(), $request->request->get('_token'))) {
            $em->remove($note);
            $em->flush();
            $this->addFlash('success', 'Note supprimée.');
        }

        return $this->redirectToRoute('note_index');
    }
}
