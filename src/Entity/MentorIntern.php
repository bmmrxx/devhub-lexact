<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mentor_intern')]
class MentorIntern
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'mentor_id', referencedColumnName: 'id', nullable: false)]
    private ?User $mentor;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'intern_id', referencedColumnName: 'id', nullable: false)]
    private ?User $intern;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMentor(): ?User
    {
        return $this->mentor;
    }

    public function setMentor(User $mentor): self
    {
        $this->mentor = $mentor;
        return $this;
    }

    public function getIntern(): ?User
    {
        return $this->intern;
    }

    public function setIntern(User $intern): self
    {
        $this->intern = $intern;
        return $this;
    }
}
