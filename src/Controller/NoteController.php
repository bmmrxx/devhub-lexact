<?php

namespace App\Controller;

use App\Entity\Upload\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    #[Route('create/note', name: 'create_note', methods: ['POST'])]
    public function createNote(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['title'], $data['content'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $note = new Note();
        $note->setUser($this->getUser());
        $note->setTitle($data['title']);
        $note->setContent($data['content']);

        $entityManager->persist($note);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Note saved!', 'id' => $note->getId()], 201);
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
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/note/delete/{id}', name: 'delete_note', methods: ['DELETE'])]
    public function deleteNote(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $note = $entityManager->getRepository(Note::class)->find($id);

        if (!$note) {
            return new JsonResponse(['error' => 'Notitie niet gevonden'], 404);
        }

        $entityManager->remove($note);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

}

