<?php

namespace App\Entity\Resources;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use App\Enum\NoteCategoryEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'note')]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    // Relatie naar User entity
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]

    private ?User $user;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: false)]
    private Project $project;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $title;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTime $created_at;

    // Opslag van categorieën als JSON array
    #[ORM\Column(type: Types::JSON)]
    private array $category = [];

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

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCategory(): array
    {
        return $this->category;
    }

    public function setCategory(array $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function hasCategory(NoteCategoryEnum $category): bool
    {
        return in_array($category->value, $this->category, true);
    }

    public function addFeedback(User $user, string $feedback): self
    {
        // Formatteer feedback als speciaal blok in de content
        // - [FEEDBACK=ID]...[/FEEDBACK] syntax
        $feedbackBlock = sprintf(
            "\n\n[FEEDBACK=%d]%s[END_FEEDBACK]",
            $user->getId(),
            $feedback
        );

        // Voeg toe aan bestaande content
        $this->content .= $feedbackBlock;

        return $this;
    }

    public function getFormattedContent(): string
    {
        // Stap 1: Verwerk code snippets
        // Syntax: (taal//code)
        $content = preg_replace_callback(
            '/\((\w+)\/\/([^)]+)\)/',  // Zoek patroon
            function ($matches) {
                // $matches[1] = programmeertaal
                // $matches[2] = code
                $language = strtolower(trim($matches[1]));
                $code = htmlspecialchars(trim($matches[2]));

                // Genereer HTML voor code block
                return "<pre class='code-block' data-language='{$language}'>"
                    . "<code>{$code}</code></pre>";
            },
            $this->content
        );

        // Stap 2: Verwerk feedback blokken
        $content = preg_replace_callback(
            '/\[FEEDBACK=(\d+)\](.*?)\[END_FEEDBACK\]/s',
            function ($matches) {
                // $matches[1] = user ID
                // $matches[2] = feedback tekst
                $userId = (int) $matches[1];
                $feedbackContent = nl2br(htmlspecialchars(trim($matches[2])));

                // Genereer HTML voor feedback block
                return "<div class='feedback-block' data-user-id='{$userId}'>"
                    . "{$feedbackContent}</div>";
            },
            $content
        );

        return $content;
    }
}