<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[Gedmo\Loggable(logEntryClass: AddressVersion::class)]
class Address extends AbstractEntity
{
    #[Assert\NotBlank(groups: ['set-address'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get-address', 'set-address', 'get-company-addresses'])]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $number = null;

    #[Assert\NotBlank(groups: ['set-address'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get-address', 'set-address', 'get-company-addresses'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $streetType = null;

    #[Assert\NotBlank(groups: ['set-address'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get-address', 'set-address', 'get-company-addresses'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $streetName = null;

    #[Assert\NotBlank(groups: ['set-address'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get-address', 'set-address', 'get-company-addresses'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $city = null;

    #[Assert\NotBlank(groups: ['set-address'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get-address', 'set-address', 'get-company-addresses'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $zipCode = null;

    #[Gedmo\Versioned]
    #[Groups(groups: ['get-address-company', 'set-address'])]
    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(
        name: 'company_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'cascade'
    )]
    private ?Company $company = null;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
