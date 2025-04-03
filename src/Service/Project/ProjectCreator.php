<?php

namespace App\Service\Project;

use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Resources\Project;

class ProjectCreator
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {

    }

    public function create(string $projectName, array $users): Project
    {
        $project = new Project();
        $project->setName($projectName);
        $project->setUser($users[0]);
        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }
}