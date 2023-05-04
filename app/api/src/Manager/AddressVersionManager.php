<?php

namespace App\Manager;

use App\Entity\Address;
use App\Entity\AddressVersion;
use App\Exception\RuntimeScriptException;
use App\Repository\AddressVersionRepository;
use Doctrine\ORM\EntityRepository;

class AddressVersionManager extends AbstractManager
{
    public function getLogEntriesByDate(Address $entity, \DateTimeInterface $datetime): ?object
    {
        if (!($repository = $this->getRepository()) instanceof AddressVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        return $repository->getLogEntriesByDate($entity, $datetime);
    }

    public function revert(Address $entity, $version = 1): void
    {
        if (!($repository = $this->getRepository()) instanceof AddressVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        $repository->revert($entity, $version);
    }

    public function getRepository(): EntityRepository|AddressVersionRepository
    {
        return $this->entityManager->getRepository(self::getClassName());
    }

    public static function getClassName(): string
    {
        return AddressVersion::class;
    }
}
