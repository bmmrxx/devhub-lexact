<?php

namespace App\Controller;

use App\Entity\Resources\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    #[Route('/notes', name: 'app_notes')]
    public function index(EntityManagerInterface $em): Response
    {
        $notes = $em->getRepository(Note::class)->findBy(
            ['user' => $this->getUser()],
            ['created_at' => 'DESC']
        );

        return $this->render('note/index.html.twig', [
            'notes' => $notes
        ]);
    }

    #[Route('/api/notes', name: 'api_note_create', methods: ['POST'])]
    public function createNote(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Create a new note from JSON data
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'], $data['content'])) {
            return new JsonResponse(['error' => 'Alle velden moeten ingevuld worden!'], 400);
        }

        $note = new Note();
        $note->setUser($this->getUser());
        $note->setTitle($data['title']);
        $note->setContent($data['content']);
        $note->setCategory($data['category'] ?? 'note');

        $em->persist($note);
        $em->flush();

        return new JsonResponse([
            'id' => $note->getId(),
            'category' => $note->getCategory()
        ], 201);
    }

    #[Route('/api/notes/{id}/feedback', name: 'api_note_feedback', methods: ['POST'])]
    public function addFeedback(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Zorgen dat alleen mentors > feedback kunnen geven
        $this->denyAccessUnlessGranted('ROLE_MENTOR');

        $note = $em->getRepository(Note::class)->find($id);
        if (!$note) {
            return new JsonResponse(['error' => 'Notitie niet gevonden'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['feedback'])) {
            return new JsonResponse(['error' => 'Feedback is required'], 400);
        }

        $note->addFeedback($this->getUser(), $data['feedback']);
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'content' => $note->getFormattedContent()
        ]);
    }

    #[Route('/api/notes/{id}', name: 'api_note_get', methods: ['GET'])]
    public function getNote(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Haal een notitie op met geformateerde inhoud
        $note = $em->getRepository(Note::class)->find($id);
        if (!$note) {
            return new JsonResponse(['error' => 'Note not found'], 404);
        }

        return new JsonResponse([
            'content' => $note->getFormattedContent(),
            'category' => $note->getCategory()
        ]);
    }
    #[Route('/notes/delete/{id}', name: 'note_delete', methods: ['POST'])]
    public function deleteNote(
        int $id,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $note = $em->getRepository(Note::class)->find($id);

        // Kijk of de notitie bestaat
        if (!$note) {
            $this->addFlash('error', 'Notitie niet gevonden');
            return $this->redirectToRoute('app_notes');
        }

        // Controleer CSRF token
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-note' . $note->getId(), $submittedToken)) {
            $this->addFlash('error', 'Invalide CSRF token');
            return $this->redirectToRoute('app_notes');
        }

        // Kijken of de user de notitie mag verwijderen
        $user = $this->getUser();
        if ($note->getUser()->getId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Je kan alleen je eigen notities verwijderen');
            return $this->redirectToRoute('app_notes');
        }

        // Verwijder de notitie
        $em->remove($note);
        $em->flush();

        $this->addFlash('success', 'Notitie succesvol verwijderd!');
        return $this->redirectToRoute('app_notes');
    }
}