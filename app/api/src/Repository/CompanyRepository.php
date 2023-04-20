<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Company $entity, bool $flush = false): void
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
            $qb->where('s.name LIKE :search')
                ->orWhere('s.url LIKE :search')
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

        return $qb->getQuery()->getResult();
    }

    public function total(?string $search = null): int
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('count(s.id)');

        if (null !== $search) {
            $qb->where('s.name LIKE :search')
                ->orWhere('s.url LIKE :search')
                ->orWhere('s.description LIKE :search');

            $qb->setParameter('search', '%'.$search.'%');
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}
