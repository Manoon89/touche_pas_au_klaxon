<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;

#[AppAssert\AgencyDeletion]

#[ORM\Entity(repositoryClass: AgencyRepository::class)]
class Agency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'agency_id')]
    private ?int $agencyId = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $city = null;

    #[ORM\OneToMany(mappedBy: 'departureAgency', targetEntity: Journey::class)]
    private $departureJourneys;

    #[ORM\OneToMany(mappedBy: 'arrivalAgency', targetEntity: Journey::class)]
    private $arrivalJourneys;

    public function __construct()
    {
        $this->departureJourneys = new ArrayCollection();
        $this->arrivalJourneys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->agencyId;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function __toString(): string
    {
        return $this->getCity() ?? '';
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartureJourneys(): Collection
    {
        return $this->departureJourneys;
    }

    public function getArrivalJourneys(): Collection
    {
        return $this->arrivalJourneys;
    }
}