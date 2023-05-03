<?php

namespace App\Manager;

use App\Entity\Company;
use App\Entity\CompanyVersion;
use App\Repository\CompanyVersionRepository;

class CompanyVersionManager extends AbstractManager
{
    public function getLogEntriesByDate(Company $entity, \DateTimeInterface $datetime): ?object
    {
        return $this->getRepository()->getLogEntriesByDate($entity, $datetime);
    }

    public function revert(Company $entity, $version = 1): void
    {
        $this->getRepository()->revert($entity, $version);
    }

    public function getRepository(): CompanyVersionRepository
    {
        /* @psalm-var CompanyVersionRepository */
        return $this->entityManager->getRepository(self::getClassName());
    }

    public static function getClassName(): string
    {
        return CompanyVersion::class;
    }
}
