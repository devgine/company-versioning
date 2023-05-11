<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager implements ManagerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function save(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->flush();
    }

    public function find(int $id): ?object
    {
        return $this->entityManager->find(static::getClassName(), $id);
    }

    public function remove(object $object): void
    {
        $this->entityManager->remove($object);
        $this->flush();
    }

    /**
     * @psalm-return class-string
     */
    abstract public static function getClassName(): string;
}
