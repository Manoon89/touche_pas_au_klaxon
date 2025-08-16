<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgencyRepository::class)]
class Agency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'agency_id')]
    private ?int $agencyId = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $city = null;

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
}