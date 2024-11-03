<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

/**
 * @extends ServiceEntityRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function save(Address $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Address $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @psalm-return array<Address>
     */
    public function search(
        ?string $search = null,
        ?string $order = null,
        ?string $sort = null,
        ?int $limit = 50,
        ?int $offset = 0
    ): array {
        $qb = $this->createQueryBuilder('s');

        if (null !== $search) {
            $qb->where('s.streetType LIKE :search')
                ->orWhere('s.streetName LIKE :search')
                ->orWhere('s.city LIKE :search')
                ->orWhere('s.zipCode LIKE :search')
                ->orWhere('s.description LIKE :search');

            $qb->setParameter('search', '%'.$search.'%');
        }

        if (null !== $sort) {
            $qb->orderBy(sprintf('s.%s', $sort), $order);
        }

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function total(?string $search = null): int
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('count(s.id)');

        if (null !== $search) {
            $qb->where('s.streetType LIKE :search')
                ->orWhere('s.streetName LIKE :search')
                ->orWhere('s.city LIKE :search')
                ->orWhere('s.zipCode LIKE :search')
                ->orWhere('s.description LIKE :search');

            $qb->setParameter('search', '%'.$search.'%');
        }

        $result = $qb->getQuery()->getSingleScalarResult();

        if (!is_int($result)) {
            throw new LogicException('[Addresses count] Type of count must be integer.');
        }

        return $result;
    }
}
