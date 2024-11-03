<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Address;
use DateTimeInterface;

class AddressVersionRepository extends AbstractVersionRepository
{
    public function getLogEntriesByDate(Address $entity, DateTimeInterface $datetime): ?object
    {
        return $this->findLogEntriesByDate($entity, $datetime);
    }
}
