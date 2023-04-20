<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address extends AbstractEntity
{
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $streetType = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $streetName = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $zipCode = null;

    #[ORM\JoinColumn(
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'cascade'
    )]
    private Company $company;

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStreetType(): ?string
    {
        return $this->streetType;
    }

    public function setStreetType(string $streetType): self
    {
        $this->streetType = $streetType;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
