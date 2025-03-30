<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NoteController extends AbstractController
{
    #[Route('/note', name: 'app_note')]
    public function index(): Response
    {
        return $this->render('note/index.html.twig', [
            'controller_name' => 'NoteController',
        ]);
    }

    #[Route('/create/note', name: 'create_note', methods: ['POST'])]
    public function createNote(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['title'], $data['content'])) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $note = new Note();
        $note->setUser($this->getUser());
        $note->setTitle($data['title']);
        $note->setContent($data['content']);
        $note->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($note);
        $entityManager->flush();

        return new JsonResponse(
            ['message' => 'Note saved!', 'id' => $note->getId()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/note', name: 'get_notes', methods: ['GET'])]
    public function getNotes(EntityManagerInterface $entityManager): JsonResponse
    {
        $notes = $entityManager->getRepository(Note::class)->findAll();

        $data = [];
        foreach ($notes as $note) {
            $data[] = [
                'id' => $note->getId(),
                'title' => $note->getTitle(),
                'content' => $note->getContent(),
                'created_at' => $note->getCreatedAt()->format('Y-m-d H:i:s'),
                'user' => $note->getUser() ? $note->getUser()->getId() : null,
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/note/delete/{id}', name: 'delete_note', methods: ['DELETE'])]
    public function deleteNote(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $note = $entityManager->getRepository(Note::class)->find($id);

        if (!$note) {
            return new JsonResponse(
                ['error' => 'Notitie niet gevonden'],
                Response::HTTP_NOT_FOUND
            );
        }

        // Optional: Add security check to ensure user can only delete their own notes
        if ($note->getUser() !== $this->getUser()) {
            return new JsonResponse(
                ['error' => 'Unauthorized'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $entityManager->remove($note);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}