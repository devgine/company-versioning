<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Gedmo\Loggable(logEntryClass: CompanyVersion::class)]
class Company extends AbstractEntity
{
    #[Assert\NotBlank(groups: ['set'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get', 'set'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[Assert\NotBlank(groups: ['set'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get', 'set'])]
    #[ORM\Column(length: 255, unique: true, nullable: false)]
    private ?string $sirenNumber = null;

    #[Assert\NotBlank(groups: ['set'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get', 'set'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $registrationCity = null;

    #[Assert\Type(type: 'datetime', groups: ['set'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get', 'set'])]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $registrationDate = null;

    #[Assert\NotBlank(groups: ['set'])]
    #[Assert\Type(type: 'float', groups: ['set'])]
    #[Gedmo\Versioned]
    #[Groups(groups: ['get', 'set'])]
    #[ORM\Column(type: Types::FLOAT, nullable: false)]
    private ?float $capital = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSirenNumber(): ?string
    {
        return $this->sirenNumber;
    }

    public function setSirenNumber(string $sirenNumber): self
    {
        $this->sirenNumber = $sirenNumber;

        return $this;
    }

    public function getRegistrationCity(): ?string
    {
        return $this->registrationCity;
    }

    public function setRegistrationCity(string $registrationCity): self
    {
        $this->registrationCity = $registrationCity;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getCapital(): ?float
    {
        return $this->capital;
    }

    public function setCapital(float $capital): self
    {
        $this->capital = $capital;

        return $this;
    }
}
