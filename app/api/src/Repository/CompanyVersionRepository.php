<?php

namespace App\Repository;

use App\Entity\Company;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Gedmo\Tool\Wrapper\EntityWrapper;

class CompanyVersionRepository extends LogEntryRepository
{
    public function getLogEntriesByDate(Company $entity, \DateTimeInterface $datetime): ?object
    {
        $wrapped = new EntityWrapper($entity, $this->_em);
        $objectClass = $wrapped->getMetadata()->getName();
        $meta = $this->getClassMetadata();
        $dql = "SELECT log FROM {$meta->getName()} log";
        $dql .= ' WHERE log.objectId = :objectId';
        $dql .= ' AND log.objectClass = :objectClass';
        $dql .= ' AND log.loggedAt < :datetime';
        $dql .= ' ORDER BY log.version DESC';

        $objectId = (string) $wrapped->getIdentifier();
        $q = $this->_em->createQuery($dql);
        $q->setParameters(compact('objectId', 'objectClass'));
        $q->setParameter('datetime', $datetime->format('Y-m-d H:i:s'));

        return 0 < count($q->getResult()) ? $q->getResult()[0] : null;
    }
}
