<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Address;
use App\Entity\AddressVersion;
use App\Exception\RuntimeScriptException;
use App\Repository\AddressVersionRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityRepository;

class AddressVersionManager extends AbstractManager
{
    public function getLogEntriesByDate(Address $entity, DateTimeInterface $datetime): ?object
    {
        $repository = $this->getRepository();

        if (!$repository instanceof AddressVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        return $repository->getLogEntriesByDate($entity, $datetime);
    }

    public function revert(Address $entity, int $version = 1): void
    {
        $repository = $this->getRepository();

        if (!$repository instanceof AddressVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        $repository->revert($entity, $version);
    }

    public function getRepository(): EntityRepository|AddressVersionRepository
    {
        return $this->entityManager->getRepository(self::getClassName());
    }

    /** @psalm-return class-string<AddressVersion> */
    public static function getClassName(): string
    {
        return AddressVersion::class;
    }
}
