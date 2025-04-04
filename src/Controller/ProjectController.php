<?php

namespace App\Controller;

use App\Entity\Resources\Project;
use App\Entity\Resources\User; // Make sure to import the User entity
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

            // Start with current user
            $users = new ArrayCollection();
            $users->add($this->getUser());

            // Add selected users if any
            $selectedUsers = $form->get('users')->getData();

            if ($selectedUsers && !$selectedUsers->isEmpty()) {
                foreach ($selectedUsers as $user) {
                    if ($user instanceof User) {
                        $user->add($user);
                    }
                }
            }

            // Remove duplicates by user ID
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

    #[Route('/project/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
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
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $em->remove($project);
            $em->flush();
            $this->addFlash('success', 'Project succesvol verwijderd!');
        }

        return $this->redirectToRoute('project_index');
    }
}