<?php

namespace App\Entity;

use App\Enum\CategoryUploadEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'intern_upload')]
class InternUpload
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $date;

    #[ORM\Column(type: 'string', enumType: CategoryUploadEnum::class)]
    private ?CategoryUploadEnum $category;

    public function __construct()
    {
        $this->date = new \DateTime();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getCategory(): ?CategoryUploadEnum
    {
        return $this->category;
    }

    public function setCategory(CategoryUploadEnum $category): self
    {
        $this->category = $category;
        return $this;
    }
}
