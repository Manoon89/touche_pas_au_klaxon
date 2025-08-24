<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[AppAssert\AgencyDeletion]
#[UniqueEntity(
    fields: ['city'],
    message: 'Une agence existe déjà pour cette ville.'
)]

#[ORM\Entity(repositoryClass: AgencyRepository::class)]

class Agency
{
    /**
     * Identifiant de l'agence
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'agency_id')]
    private $agencyId = null;

    /**
     * Nom de la ville
     * 
     * @var string|null
     */
    #[ORM\Column(length: 100, unique: true)]
    private ?string $city = null;

    /**
     * Agence de départ pour les trajets
     * 
     * @var Collection<int, \App\Entity\Journey>
     */
    #[ORM\OneToMany(mappedBy: 'departureAgency', targetEntity: Journey::class)]
    private Collection $departureJourneys;

    /**
     * Agence d'arrivée pour les trajets
     * 
     * @var Collection<int, \App\Entity\Journey>
     */
    #[ORM\OneToMany(mappedBy: 'arrivalAgency', targetEntity: Journey::class)]
    private Collection $arrivalJourneys;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->departureJourneys = new ArrayCollection();
        $this->arrivalJourneys = new ArrayCollection();
    }

    /**
     * Retourne le n° d'identifiant de l'agence
     * 
     */
    public function getId(): ?int
    {
        return $this->agencyId;
    }

    /**
     * Retourne la ville de l'agence
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Retourne la ville de l'agence en chaîne de caractères
     */
    public function __toString(): string
    {
        return $this->getCity() ?? '';
    }

    /**
     * Définit la ville de l'agence
     */
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Retourne les trajets avec l'agence en départ
     */
    public function getDepartureJourneys(): Collection
    {
        return $this->departureJourneys;
    }

    /**
     * Retourne les trajets avec l'agence en arrivée
     */
    public function getArrivalJourneys(): Collection
    {
        return $this->arrivalJourneys;
    }
}