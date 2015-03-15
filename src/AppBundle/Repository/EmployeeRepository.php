<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EmployeeRepository extends EntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('e');
        $query = $qb->select($qb->expr()->count('e'))->getQuery();

        return $query->getSingleScalarResult();
    }
}
