<?php

namespace App\Repository;

use App\Entity\LegalStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LegalStatus>
 *
 * @method LegalStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalStatus[]    findAll()
 * @method LegalStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalStatus::class);
    }

    public function save(LegalStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LegalStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(
        ?string $search = null,
        ?string $order = null,
        ?string $sort = null,
        ?int $limit = 50,
        ?int $offset = 0
    ): array {
        $qb = $this->createQueryBuilder('s');

        if (null !== $search) {
            $qb->where('s.label LIKE :search');

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

        return $qb->getQuery()->getResult();
    }

    public function total(?string $search = null): int
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('count(s.id)');

        if (null !== $search) {
            $qb->where('s.label LIKE :search');

            $qb->setParameter('search', '%'.$search.'%');
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}
