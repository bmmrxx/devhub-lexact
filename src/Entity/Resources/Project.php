<?php

namespace App\Entity\Resources;

use App\Entity\User;
use App\Entity\Resources\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity]
#[ORM\Table(name: 'project')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    #[JoinTable(name: 'user_project')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    // #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'project')]
    // private $file;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
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
    public function addUser(User $user): void
    {
        $user->addProject($this);
        $this->users[] = $user;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }
}
;