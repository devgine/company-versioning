<?php

namespace App\Manager;

use App\Entity\LegalStatus;
use App\Repository\LegalStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class LegalStatusManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManager, protected LegalStatusRepository $repository)
    {
        parent::__construct($entityManager);
    }

    /** @psalm-return array<LegalStatus> */
    public function search(?string $search = null): array
    {
        return $this->repository->search($search);
    }

    /** @psalm-return class-string<LegalStatus> */
    public static function getClassName(): string
    {
        return LegalStatus::class;
    }
}
