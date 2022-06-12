<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\EmployeeGroup;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmployeeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function countByFilters(?EmployeeGroup $group = null): int
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->select('count(e.id)');
        if ($group) {
            $qb->andWhere('e.employeeGroup = :group')
                ->setParameter('group', $group);
        } else {
            $qb->andWhere("e.employeeGroup != :group OR e.employeeGroup is NULL")
                ->setParameter('group', EmployeeGroup::EPOCH_ID);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Employee[]
     * @throws \Doctrine\ORM\ORMException
     */
    public function findByFilters(int $limit, int $page, int $seed, ?EmployeeGroup $group = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit);
        if (0 != $seed) {
            $qb->orderBy('RAND(:seed)')
                ->setParameter('seed', $seed);
        } else {
            $qb->orderBy('e.orderPosition', 'ASC');
        }

        if ($group) {
            $qb->andWhere('e.employeeGroup = :group')
                ->setParameter('group', $group);
        } else {
            $qb->andWhere("e.employeeGroup != :group OR e.employeeGroup is NULL")
                ->setParameter('group', EmployeeGroup::EPOCH_ID);
        }

        return $qb->getQuery()->execute();
    }
}
