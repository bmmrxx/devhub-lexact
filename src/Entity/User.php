<?php

namespace App\Entity;

use App\Entity\Resources\Project;
use App\Enum\UserRoleEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP', 'updatable' => false])]
    private \DateTime $created_at;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ManyToMany(targetEntity: Project::class, mappedBy: 'users')]
    private Collection $projects;

    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->projects = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName() ?? $this->getEmail() ?? (string) $this->getId();
    }

    public function getId(): int
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = UserRoleEnum::INTERN->value;
        }
        
        return array_unique($roles);
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role instanceof UserRoleEnum ? $role : UserRoleEnum::tryFrom($role));
        }
        return $this;
    }

    public function addRole(UserRoleEnum|string|null $role): self
    {
        if (!$role) {
            return $this;
        }

        $roleValue = $role instanceof UserRoleEnum ? $role->value : UserRoleEnum::tryFrom($role)?->value;

        if ($roleValue && !in_array($roleValue, $this->roles, true)) {
            $this->roles[] = $roleValue;
        }
        
        return $this;
    }

    public function removeRole(UserRoleEnum|string $role): self
    {
        $roleValue = $role instanceof UserRoleEnum ? $role->value : $role;
        $this->roles = array_filter($this->roles, fn($r) => $r !== $roleValue);

        return $this;
    }

    public function hasRole(UserRoleEnum|string $role): bool
    {
        $roleValue = $role instanceof UserRoleEnum ? $role->value : $role;
        return in_array($roleValue, $this->getRoles(), true);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    public function addProject(Project $project): void
    {
        $this->projects[] = $project;
    }
    public function removeProject(Project $project): self
    {
        $this->projects->removeElement($project);
        return $this;
    }
    public function getProjects(): Collection
    {
        return $this->projects;
    }
}
