<?php

namespace App\Repository;

use App\Entity\Company;
use DateTimeInterface;

class CompanyVersionRepository extends AbstractVersionRepository
{
    public function getLogEntriesByDate(Company $entity, DateTimeInterface $datetime): ?object
    {
        return $this->findLogEntriesByDate($entity, $datetime);
    }
}
