<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager implements ManagerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function save(object $entity, bool $flush = true): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function find(int $id): ?object
    {
        return $this->entityManager->find(static::getClassName(), $id);
    }

    public function remove(object $object, bool $flush = true)
    {
        $this->entityManager->remove($object);

        if ($flush) {
            $this->flush();
        }
    }

    abstract public static function getClassName(): string;
}
