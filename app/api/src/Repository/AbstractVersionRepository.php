<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\VersionInterface;
use DateTimeInterface;
use Doctrine\ORM\NonUniqueResultException;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Gedmo\Tool\Wrapper\EntityWrapper;
use LogicException;

abstract class AbstractVersionRepository extends LogEntryRepository
{
    /**
     * @psalm-return <VersionInterface>
     *
     * @throws NonUniqueResultException
     */
    public function findLogEntriesByDate(AbstractEntity $entity, DateTimeInterface $datetime): ?object
    {
        $wrapped = new EntityWrapper($entity, $this->_em);
        $objectClass = $wrapped->getMetadata()->getName();
        $meta = $this->getClassMetadata();
        $dql = "SELECT log FROM {$meta->getName()} log";
        $dql .= ' WHERE log.objectId = :objectId';
        $dql .= ' AND log.objectClass = :objectClass';
        $dql .= ' AND log.loggedAt < :datetime';
        $dql .= ' ORDER BY log.version DESC';

        $objectId = $wrapped->getIdentifier();

        if (!is_string($objectId) && !is_int($objectId)) {
            throw new LogicException('ObjectId should be type of string');
        }

        $q = $this->_em->createQuery($dql);
        $q->setParameters(compact('objectId', 'objectClass'));
        $q->setParameter('datetime', $datetime->format('Y-m-d H:i:s'));

        if (!$q->getOneOrNullResult() instanceof VersionInterface) {
            throw new LogicException('Result should be instance of VersionInterface');
        }

        return $q->getOneOrNullResult();
    }
}
