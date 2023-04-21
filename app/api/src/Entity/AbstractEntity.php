<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

class AbstractEntity
{
    #[Groups(groups: ['get'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(groups: ['get'])]
    #[ORM\Column(type: 'datetime', nullable: false)]
    protected ?\DateTimeInterface $createdDate;

    #[Gedmo\Timestampable(on: 'update')]
    #[Groups(groups: ['get'])]
    #[ORM\Column(type: 'datetime', nullable: false)]
    protected ?\DateTimeInterface $lastUpdateDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(?\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->lastUpdateDate;
    }

    public function setLastUpdateDate(?\DateTimeInterface $lastUpdateDate): self
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }
}
