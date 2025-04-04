<?php

namespace App\Entity\Resources;

use App\Entity\User;
use App\Entity\Resources\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'project')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    // Relatie naar User entity
    #[ORM\OneToMany(targetEntity: UserProject::class, mappedBy: 'project')]
    private Collection $userProjects;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'project')]
    private $file;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public function __construct()
    {
        $this->userProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|UserProject[]
     */
    public function getUserProjects(): Collection
    {
        return $this->userProjects;
    }

    public function addUserProject(UserProject $userProject): self
    {
        if (!$this->userProjects->contains($userProject)) {
            $this->userProjects[] = $userProject;
            $userProject->setProject($this);
        }
        return $this;
    }

    public function removeUserProject(UserProject $userProject): self
    {
        if ($this->userProjects->removeElement($userProject)) {
            // Only nullify if this user is still set
            if ($userProject->getUser() === $this) {
                $userProject->setUser(null);
            }
        }
        return $this;
    }
}
