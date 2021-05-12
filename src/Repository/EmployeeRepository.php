<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\EmployeeGroup;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmployeeRepository extends AbstractRepository
{
    private TranslatorInterface $translator;

    public function __construct(ManagerRegistry $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, Employee::class);
        $this->translator = $translator;
    }

    public function countByFilters(?EmployeeGroup $group = null): int
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->select('count(e.id)');
        if ($group) {
            $qb->andWhere('e.employeeGroup = :group')
                ->setParameter('group', $group);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Employee[]
     * @throws \Doctrine\ORM\ORMException
     */
    public function findByFilters(int $limit, int $page, int $seed, string $locale, ?EmployeeGroup $group = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit);
        if (0 != $seed) {
            $qb->orderBy('RAND(:seed)')
                ->setParameter('seed', $seed);
        } else {
            $qb->orderBy('e.lastName', 'ASC');
        }

        if ($group) {
            $qb->andWhere('e.employeeGroup = :group')
                ->setParameter('group', $group);
        }

        return $qb->getQuery()->execute();
    }
}
