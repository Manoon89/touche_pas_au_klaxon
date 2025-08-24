<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Validator\Constraints as AppAssert;

#[AppAssert\UserDeletion]

#[ORM\Entity(repositoryClass: UserRepository::class)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Trajets associés à l'utilisateur
     * 
     * @var Collection<int, Journey>
     */
    #[ORM\OneToMany(mappedBy: "user", targetEntity: Journey::class)]
    private Collection $journeys;

    /**
     * Identifiant de l'utilisateur
     * 
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'user_id')]
    private $userId = null;

    /**
     * Nom de famille de l'utilisateur
     * 
     * @var string|null
     */
    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    /**
     * Prénom de l'utilisateur
     * 
     * @var string|null
     */
    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    /**
     * Numéro de téléphone de l'utilisateur
     * 
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $phone = null;

    /**
     * Email de l'utilisateur
     * 
     * @var string|null
     */
    #[ORM\Column(length: 150, unique: true)]
    private ?string $email = null;

    /**
     * Mot de passe de l'utilisateur
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * Rôles de l'utilisateur stockés en JSON
     * 
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->journeys = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant de l'utilisateur
     */
    public function getId(): ?int
    {
        return $this->userId;
    }

    /**
     * Retourne le nom de famille de l'utilisateur
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Définit le nom de famille de l'utilisateur
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Retourne le prénom de l'utilisateur
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Définit le prénom de l'utilisateur
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /** 
     * Retourne le numéro de téléphone de l'utilisateur
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Définit le numéro de téléphone de l'utilisateur
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Retourne l'email de l'utilisateur
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /** 
     * Définit l'email de l'utilisateur
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /** 
     * Retourne le mot de passe de l'utilisateur
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe de l'utilisateur
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Retourne les rôles de l'utilisateur
     * Ajoute par défaut ROLE_USER pour chaque utilisateur
     * 
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur
     * 
     * @param array<int, string> $roles
     * @return $this
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Retourne l'identifiant unique de connexion (email)
     * 
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Vider les données sensibles temporaires ici si utilisées en clair
    }

    /**
     * Retourne les trajets associés à l'utilisateur
     * 
     * @return Collection<int, Journey>
     */
    public function getJourneys(): Collection
    {
        return $this->journeys;
    }
}