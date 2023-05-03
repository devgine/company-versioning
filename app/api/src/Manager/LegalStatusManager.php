<?php

namespace App\Manager;

use App\Entity\LegalStatus;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;

class LegalStatusManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManager, protected CompanyRepository $repository)
    {
        parent::__construct($entityManager);
    }

    public function search(?string $search = null): array
    {
        return $this->repository->search($search);
    }

    public static function getClassName(): string
    {
        return LegalStatus::class;
    }
}
