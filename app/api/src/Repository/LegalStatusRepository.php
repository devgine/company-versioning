<?php

namespace App\Repository;

use App\Entity\LegalStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

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

    public function save(LegalStatus $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(LegalStatus $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /** @psalm-return array<LegalStatus> */
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

        return $qb->getQuery()->getArrayResult();
    }

    public function total(?string $search = null): int
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('count(s.id)');

        if (null !== $search) {
            $qb->where('s.label LIKE :search');

            $qb->setParameter('search', '%'.$search.'%');
        }

        $result = $qb->getQuery()->getSingleScalarResult();

        if (!is_int($result)) {
            throw new LogicException('[Legal statuses count] Type of count must be integer.');
        }

        return $result;
    }
}
