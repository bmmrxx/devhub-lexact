<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'file')]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user;

    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'file')]
    #[ORM\JoinTable(name: 'project')]
    private $projects;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\Datetime $uploaded_at;

    public function __construct()
    {
        $this->uploaded_at = new \DateTime();
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

    public function getUploadedAt(): ?\Datetime
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\Datetime $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;
        return $this;
    }

    public function getProjects(): \Doctrine\Common\Collections\Collection
    {
        return $this->projects;
    }
}
