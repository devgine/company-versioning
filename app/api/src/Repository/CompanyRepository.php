<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

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

    public function save(Company $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Company $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /** @psalm-return array<Company> */
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
                ->orWhere('s.sirenNumber LIKE :search')
                ->orWhere('s.registrationCity LIKE :search')
                ->orWhere('s.legalStatus LIKE :search');

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
            $qb->where('s.name LIKE :search')
                ->orWhere('s.sirenNumber LIKE :search')
                ->orWhere('s.registrationCity LIKE :search')
                ->orWhere('s.legalStatus LIKE :search');

            $qb->setParameter('search', '%'.$search.'%');
        }

        $result = $qb->getQuery()->getSingleScalarResult();

        if (!is_int($result)) {
            throw new LogicException('[Companies count] Type of count must be integer.');
        }

        return $result;
    }
}
