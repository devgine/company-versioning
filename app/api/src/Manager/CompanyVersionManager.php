<?php

namespace App\Manager;

use App\Entity\Company;
use App\Entity\CompanyVersion;
use App\Exception\RuntimeScriptException;
use App\Repository\CompanyVersionRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityRepository;

class CompanyVersionManager extends AbstractManager
{
    public function getLogEntriesByDate(Company $entity, DateTimeInterface $datetime): ?object
    {
        $repository = $this->getRepository();

        if (!$repository instanceof CompanyVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        return $repository->getLogEntriesByDate($entity, $datetime);
    }

    public function revert(Company $entity, int $version = 1): void
    {
        $repository = $this->getRepository();

        if (!$repository instanceof CompanyVersionRepository) {
            throw new RuntimeScriptException('Repository must be instance of AddressVersionRepository');
        }

        $repository->revert($entity, $version);
    }

    public function getRepository(): EntityRepository|CompanyVersionRepository
    {
        return $this->entityManager->getRepository(self::getClassName());
    }

    /** @psalm-return class-string<CompanyVersion> */
    public static function getClassName(): string
    {
        return CompanyVersion::class;
    }
}
