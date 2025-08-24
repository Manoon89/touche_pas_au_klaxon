<?php

namespace App\Entity;

use App\Repository\JourneyRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Agency;
use App\Entity\User;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[AppAssert\JourneyDates]
#[AppAssert\JourneyCities]
#[AppAssert\JourneySeats]

#[ORM\Entity(repositoryClass: JourneyRepository::class)]

class Journey
{
    /**
     * Identifiant du trajet
     * 
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'journey_id')]
    private $journeyId = null;

    /**
     * Date de départ du trajet
     * 
     * @var \DateTime|null
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $departureDate = null;

    /**
     * Date d'arrivée du trajet
     * 
     * @var \DateTime|null
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $arrivalDate = null;

    /**
     * Nombre de sièges total du véhicule utilisé pour le trajet
     * 
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Le nombre de sièges total ne peut pas être négatif.")]
    private ?int $totalSeats = null;

    /**
     * Nombre de sièges encore disponibles pour le trajet
     * 
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Le nombre de sièges disponibles ne peut pas être négatif.")]
    private ?int $availableSeats = null;

    /**
     * Utilisateur créateur du trajet
     * 
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "journeys")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false)]
    private ?User $user = null;

    /**
     * Agence de départ du trajet
     * 
     * @var Agency|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'departure_agency_id', referencedColumnName: 'agency_id', nullable: false)]
    private ?Agency $departureAgency = null;

    /**
     * Agence d'arrivée du trajet
     * 
     * @var Agency|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'arrival_agency_id', referencedColumnName: 'agency_id', nullable: false)]
    private ?Agency $arrivalAgency = null;

    /**
     * Retourne l'identifiant du trajet
     */
    public function getId(): ?int
    {
        return $this->journeyId;
    }

    /**
     * Retourne la date de départ du trajet
     */
    public function getDepartureDate(): ?\DateTime
    {
        return $this->departureDate;
    }

    /**
     * Définit la date de départ du trajet
     */
    public function setDepartureDate(\DateTime $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /** 
     * Retourne la date d'arrivée du trajet
     */
    public function getArrivalDate(): ?\DateTime
    {
        return $this->arrivalDate;
    }

    /**
     * Définit la date d'arrivée du trajet
     */
    public function setArrivalDate(\DateTime $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /** 
     * Retourne le nombre total de sièges dans le véhicule
     */
    public function getTotalSeats(): ?int
    {
        return $this->totalSeats;
    }

    /**
     * Définit le nombre total de sièges dans le véhicule
     */
    public function setTotalSeats(int $totalSeats): static
    {
        $this->totalSeats = $totalSeats;

        return $this;
    }

    /**
     * Retourne le nombre de sièges disponibles dans le véhicule
     */
    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    /**
     * Définit le nombre de sièges disponibles dans le véhicule
     */
    public function setAvailableSeats(int $availableSeats): static
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    /**
     * Retourne l'utilisateur créateur du trajet
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /** 
     * Définit l'utilisateur créateur du trajet 
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /** 
     * Retourne l'agence de départ du trajet
     */
    public function getDepartureAgency(): ?Agency
    {
        return $this->departureAgency;
    }

    /** 
     * Définit l'agence de départ du trajet
     */
    public function setDepartureAgency(?Agency $departureAgency): static
    {
        $this->departureAgency = $departureAgency;

        return $this;
    }

    /**
     * Retourne l'agence d'arrivée du trajet
     */
    public function getArrivalAgency(): ?Agency
    {
        return $this->arrivalAgency;
    }

    /** 
     * Définit l'agence d'arrivée du trajet
     */
    public function setArrivalAgency(?Agency $arrivalAgency): static
    {
        $this->arrivalAgency = $arrivalAgency;

        return $this;
    }
}
