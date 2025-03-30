<?php

namespace App\Entity\Resources;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'note')]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(
        type: Types::STRING,
        length: 20,
        options: ["default" => "note"]
    )]
    #[Assert\Choice(['note', 'question'])]
    private string $category = 'note';
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

    public function getCreated_at(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreated_at(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function addFeedback(User $mentor, string $feedback): self
    {
        $feedbackBlock = sprintf(
            "\n\n[FEEDBACK=%d]%s[END_FEEDBACK]",
            $mentor->getId(),
            $feedback
        );
        $this->content .= $feedbackBlock;
        return $this;
    }

    public function getFormattedContent(): string
    {
        // Code snippets kunnen toevoegen in de notes
        $content = preg_replace_callback(
            '/\((\w+)\/\/([^)]+)\)/',
            function ($matches) {
                $language = strtolower(trim($matches[1]));
                $code = htmlspecialchars(trim($matches[2]));
                return "<pre class='code-block' data-language='{$language}'><code>{$code}</code></pre>";
            },
            $this->content
        );

        // Feedback toevoegen aan de note
        $content = preg_replace_callback(
            '/\[FEEDBACK=(\d+)\](.*?)\[END_FEEDBACK\]/s',
            function ($matches) {
                $userId = (int) $matches[1];
                $feedbackContent = nl2br(htmlspecialchars(trim($matches[2])));
                return "<div class='feedback-block' data-user-id='{$userId}'>{$feedbackContent}</div>";
            },
            $content
        );

        return $content;
    }
}