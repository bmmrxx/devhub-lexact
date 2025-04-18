<?php

namespace App\Entity\Resources;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

#[ORM\Entity]
#[ORM\Table(name: 'file')]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    // Relatie naar User entity
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    // #[ORM\ManyToOne(targetEntity: Project::class, 'files')]
    // #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: false)]
    // private Project $project;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $file_path;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $file_type;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $created_at;
    
    private ?SymfonyFile $uploadedFile;

    public function __construct()
    {
        $this->created_at = new \DateTime();
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
    // public function getProject(): ?Project
    // {
    //     return $this->project;
    // }

    // public function setProject(Project $project): self
    // {
    //     $this->project = $project;
    //     return $this;
    // }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getFilePath(): string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): self
    {
        $this->file_path = $file_path;
        return $this;
    }

    public function getFileType(): string
    {
        return $this->file_type;
    }

    public function setFileType(string $file_type): self
    {
        $this->file_type = $file_type;
        return $this;
    }

    public function getCreatedAt(): ?\Datetime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\Datetime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUploadedFile(): ?SymfonyFile
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile(?SymfonyFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;
        
        // Update het file type automatisch
        if ($uploadedFile) {
            $this->file_type = $uploadedFile->guessExtension() ?? 'unknown';
        }
        
        return $this;
    }
}
