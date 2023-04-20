<?php

namespace App\Entity;

use App\Repository\LegalStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LegalStatusRepository::class)]
class LegalStatus extends AbstractEntity
{
    #[Groups(groups: ['get-legal-status'])]
    #[ORM\Column(length: 255)]
    private ?string $label = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
