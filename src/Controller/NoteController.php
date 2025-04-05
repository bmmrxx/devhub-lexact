<?php

namespace App\Controller;

use App\Entity\Resources\Note;
use App\Entity\Resources\Project;
use App\Enum\NoteCategoryEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notes')]
class NoteController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('', name: 'app_notes', methods: ['GET'])]
    public function index(): Response
    {
        $notes = $this->em->getRepository(Note::class)->findBy(
            ['user' => $this->getUser()],
            ['created_at' => 'DESC']
        );

        return $this->render('note/index.html.twig', [
            'notes' => $notes
        ]);
    }

    #[Route('/new', name: 'note_new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $category = $request->request->get('category');
            $projectId = $request->request->get('project');

            if (empty($title) || empty($content) || empty($category) || empty($projectId)) {
                $this->addFlash('error', 'Alle velden moeten ingevuld worden!');
                return $this->redirectToRoute('note_new');
            }

            $project = $this->em->getRepository(Project::class)->findOneBy(['id' => $projectId]);
            // dd($project);

            $note = new Note();
            $note->setUser($user)
                ->setTitle($title)
                ->setContent($content)
                ->setCategory([$category])
                ->setProject($project);

            $this->em->persist($note);
            $this->em->flush();

            $this->addFlash('success', 'Notitie succesvol opgeslagen!');
            return $this->redirectToRoute('app_notes');
        }

        return $this->render(
            'note/new.html.twig',
            [
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ]
        );
    }

    #[Route('/{id}/feedback', name: 'note_add_feedback', methods: ['POST'])]
    public function addFeedback(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MENTOR');

        $note = $this->em->getRepository(Note::class)->find($id);
        if (!$note) {
            $this->addFlash('error', 'Notitie niet gevonden');
            return $this->redirectToRoute('app_notes');
        }

        $feedback = $request->request->get('feedback');
        if (empty($feedback)) {
            $this->addFlash('error', 'Feedback kan niet leeg zijn');
            return $this->redirectToRoute('note_get', ['id' => $id]);
        }

        $note->addFeedback($this->getUser(), $feedback);
        $this->em->flush();

        $this->addFlash('success', 'Feedback succesvol toegevoegd!');
        return $this->redirectToRoute('note_get', ['id' => $id]);
    }

    #[Route('/{id}/delete', name: 'note_delete', methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        $note = $this->em->getRepository(Note::class)->find($id);
        $user = $this->getUser();

        if (!$note) {
            $this->addFlash('error', 'Notitie niet gevonden!');
            return $this->redirectToRoute('app_notes');
        }

        if ($note->getUser()->getId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'niet gemachtigd');
            return $this->redirectToRoute('app_notes');
        }

        $this->em->remove($note);
        $this->em->flush();

        $this->addFlash('success', 'Notitie succesvol verwijderd!');
        return $this->redirectToRoute('app_notes');
    }

    #[Route('/{id}', name: 'note_get', methods: ['GET'])]
    public function getNote(int $id): Response
    {
        $note = $this->em->getRepository(Note::class)->find($id);

        if (!$note) {
            throw $this->createNotFoundException('Notitie niet gevonden!');
        }

        return $this->render('note/view.html.twig', [
            'note' => $note,
            'isMentor' => $this->isGranted('ROLE_MENTOR')
        ]);
    }

    #[Route('/{id}/edit', name: 'note_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $note = $this->em->getRepository(Note::class)->find($id);

        if (!$note) {
            $this->addFlash('error', 'Notitie niet gevonden');
            return $this->redirectToRoute('app_notes');
        }

        // Alleen eigenaar of admin mag bewerken
        if ($note->getUser()->getId() !== $this->getUser()->getId() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Geen rechten om deze notitie te bewerken');
            return $this->redirectToRoute('app_notes');
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $category = $request->request->get('category');

            if (empty($title) || empty($content) || empty($category)) {
                $this->addFlash('error', 'Titel, inhoud en categorie zijn verplicht');
                return $this->redirectToRoute('note_edit', ['id' => $id]);
            }

            $note->setTitle($title)
                ->setContent($content)
                ->setCategory([$category]);

            $this->em->flush();

            $this->addFlash('success', 'Notitie succesvol bijgewerkt');
            return $this->redirectToRoute('note_get', ['id' => $id]);
        }

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'categories' => NoteCategoryEnum::cases()
        ]);
    }
}