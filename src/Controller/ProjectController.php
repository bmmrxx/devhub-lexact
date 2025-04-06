<?php

namespace App\Controller;

use App\Entity\Resources\Project;
use App\Entity\Resources\User;
use App\Form\ProjectForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Project\ProjectCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/project', name: 'project_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        $projects = $user->getProjects();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/project/new', name: 'project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectCreator $projectCreator): Response
    {
        $form = $this->createForm(ProjectForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            // Voeg de huidige gebruiker toe
            $users = new ArrayCollection();
            $users->add($this->getUser());

            // Voeg de geselecteerde gebruikers toe
            $selectedUsers = $form->get('users')->getData();

            if ($selectedUsers && !$selectedUsers->isEmpty()) {
                foreach ($selectedUsers as $user) {
                    if ($user instanceof User) {
                        $user->add($user);
                    }
                }
            }

            // Verwijder dubbele gebruikers
            $uniqueUsers = [];
            foreach ($users as $user) {
                $uniqueUsers[$user->getId()] = $user;
            }


            try {
                $this->em->persist($formData);
                $this->em->flush();
                $this->addFlash('success', 'Project aangemaakt!');
                return $this->redirectToRoute('project_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Er is een fout opgetreden: ' . $e->getMessage());
            }
        }

        return $this->render('project/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('project/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        $user = $this->getUser();

        // Controleer of gebruiker lid is van het project
        if (!$project->getUsers()->contains($user) && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Geen toegang tot dit project');
        }

        return $this->render('project/show.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/project/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjectForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Project succesvol bijgewerkt!');
            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/{id}/delete', name: 'project_delete', methods: ['POST'])]
    public function delete(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            error_log('CSRF token invalid');
            $this->addFlash('error', 'Ongeldig CSRF token. Probeer het opnieuw.');
            return $this->redirectToRoute('project_index');
        }

        $confirmed = $request->request->get('confirm') === 'yes';
        $hasNotes = !$project->getNotes()->isEmpty();

        error_log('Confirmed: ' . ($confirmed ? 'yes' : 'no'));
        error_log('Has notes: ' . ($hasNotes ? 'yes' : 'no'));

        // Toon bevestiging als nodig
        if ($hasNotes && !$confirmed) {
            return $this->render('project/delete_confirm.html.twig', [
                'project' => $project,
                'hasNotes' => $hasNotes,
                'csrf_token' => $request->request->get('_token')
            ]);
        }

        try {
            $em->remove($project);
            $em->flush();
            error_log('Project succesvol verwijderd');

            $message = $hasNotes
                ? 'Project en alle bijbehorende bestanden succesvol verwijderd!'
                : 'Project succesvol verwijderd!';

            $this->addFlash('success', $message);
        } catch (\Exception $e) {
            error_log('Deletion error: ' . $e->getMessage());
            $this->addFlash('error', 'Fout bij verwijderen project: ' . $e->getMessage());
        }

        return $this->redirectToRoute('project_index');
    }
}