<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\EmployeeGroup;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmployeeRepository extends AbstractRepository
{
    private TranslatorInterface $translator;

    public function __construct(ManagerRegistry $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, Employee::class);
        $this->translator = $translator;
    }

    /**
     * @return Employee[]
     * @throws \Doctrine\ORM\ORMException
     */
    public function rand(int $limit, int $page, int $seed, string $locale, ?EmployeeGroup $group = null): array
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

        $employees = $qb
            ->getQuery()
            ->execute()
        ;

        $employeesTranslated = [];

        foreach ($employees as $employee) {
            $employee->setLocale($locale);
            $this->_em->refresh($employee);

            if ($employee->getTranslations()) {
                $employee->unsetTranslations();
            }

            $this->translator->setLocale($locale);
            $employee->setPosition($this->translator->trans($employee->getPosition()));

            $employeesTranslated[] = $employee;
        }

        return $employeesTranslated;
    }
}
