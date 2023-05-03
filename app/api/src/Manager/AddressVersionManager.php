<?php

namespace App\Manager;

use App\Entity\Address;
use App\Entity\AddressVersion;
use App\Repository\AddressVersionRepository;

class AddressVersionManager extends AbstractManager
{
    public function getLogEntriesByDate(Address $entity, \DateTimeInterface $datetime): ?object
    {
        return $this->getRepository()->getLogEntriesByDate($entity, $datetime);
    }

    public function revert(Address $entity, $version = 1): void
    {
        $this->getRepository()->revert($entity, $version);
    }

    public function getRepository(): AddressVersionRepository
    {
        /* @psalm-var AddressVersionRepository */
        return $this->entityManager->getRepository(self::getClassName());
    }

    public static function getClassName(): string
    {
        return AddressVersion::class;
    }
}
