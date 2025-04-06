<?php

namespace App\Service\Project;

use App\Entity\Resources\Project;
use App\Entity\Resources\UserProject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;



class ProjectCreator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(string $name, ArrayCollection $users): Project
    {
        $project = new Project();
        $project->setName($name);

        foreach ($users as $user) {
            $project->addUser($user);
        }

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }
}