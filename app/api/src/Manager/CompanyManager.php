<?php

namespace App\Manager;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompanyManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManager, protected CompanyRepository $repository)
    {
        parent::__construct($entityManager);
    }

    public function search(
        ?string $search = null,
        ?string $order = null,
        ?string $sort = null,
        ?int $limit = 50,
        ?int $offset = 0
    ): array {
        return $this->repository->search($search, $order, $sort, $limit, $offset);
    }

    public function total(?string $search = null): int
    {
        return $this->repository->total($search);
    }

    public static function getClassName(): string
    {
        return Company::class;
    }
}
