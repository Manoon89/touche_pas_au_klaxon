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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'journey_id')]
    private $journeyId = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $departureDate = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $arrivalDate = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Le nombre de sièges total ne peut pas être négatif.")]
    private ?int $totalSeats = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Le nombre de sièges total ne peut pas être négatif.")]
    private ?int $availableSeats = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "journeys")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'departure_agency_id', referencedColumnName: 'agency_id', nullable: false)]
    private ?Agency $departureAgency = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'arrival_agency_id', referencedColumnName: 'agency_id', nullable: false)]
    private ?Agency $arrivalAgency = null;

    public function getId(): ?int
    {
        return $this->journeyId;
    }

    public function getDepartureDate(): ?\DateTime
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTime $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getArrivalDate(): ?\DateTime
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTime $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getTotalSeats(): ?int
    {
        return $this->totalSeats;
    }

    public function setTotalSeats(int $totalSeats): static
    {
        $this->totalSeats = $totalSeats;

        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): static
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDepartureAgency(): ?Agency
    {
        return $this->departureAgency;
    }

    public function setDepartureAgency(?Agency $departureAgency): static
    {
        $this->departureAgency = $departureAgency;

        return $this;
    }

    public function getArrivalAgency(): ?Agency
    {
        return $this->arrivalAgency;
    }

    public function setArrivalAgency(?Agency $arrivalAgency): static
    {
        $this->arrivalAgency = $arrivalAgency;

        return $this;
    }
}
