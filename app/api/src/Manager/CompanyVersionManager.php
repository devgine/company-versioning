<?php

namespace App\Manager;

use App\Entity\Company;
use App\Entity\CompanyVersion;
use App\Exception\RuntimeScriptException;
use App\Repository\CompanyVersionRepository;
use Doctrine\ORM\EntityRepository;

class CompanyVersionManager extends AbstractManager
{
    public function getLogEntriesByDate(Company $entity, \DateTimeInterface $datetime): ?object
    {
        if (!($repository = $this->getRepository()) instanceof CompanyVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        return $repository->getLogEntriesByDate($entity, $datetime);
    }

    public function revert(Company $entity, $version = 1): void
    {
        if (!($repository = $this->getRepository()) instanceof CompanyVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        $repository->revert($entity, $version);
    }

    public function getRepository(): EntityRepository|CompanyVersionRepository
    {
        return $this->entityManager->getRepository(self::getClassName());
    }

    public static function getClassName(): string
    {
        return CompanyVersion::class;
    }
}
