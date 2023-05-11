<?php

namespace App\Manager;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;

class AddressManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManager, protected AddressRepository $repository)
    {
        parent::__construct($entityManager);
    }

    /** @psalm-return array<Address> */
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

    /** @psalm-return class-string<Address> */
    public static function getClassName(): string
    {
        return Address::class;
    }
}
