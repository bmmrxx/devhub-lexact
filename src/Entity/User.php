<?php

namespace App\Entity;

use App\Enum\UserRoleEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $password;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $created_at;

    // Opslag van rollen als JSON array
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    public function __construct()
    {
        $this->created_at = new \DateTime();
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
        // Ervoor zorgen dat een gebruiker altijd een rol heeft
        $roles = $this->roles;
        
        // Als er geen role is gespcificeerd is het standaard een intern
        if (empty($roles)) {
            $roles[] = UserRoleEnum::INTERN->value;
        }
        
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $validRoles = array_map(fn($role) => UserRoleEnum::tryFrom($role)?->value, $roles);
        $this->roles = array_filter($validRoles);
        
        return $this;
    }

    public function addRole(UserRoleEnum $role): self
    {
        if (!in_array($role->value, $this->roles, true)) {
            $this->roles[] = $role->value;
        }
        
        return $this;
    }

    public function removeRole(UserRoleEnum $role): self
    {
        $this->roles = array_filter($this->roles, fn($r) => $r !== $role->value);
        
        return $this;
    }

    public function hasRole(UserRoleEnum $role): bool
    {
        return in_array($role->value, $this->getRoles(), true);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
