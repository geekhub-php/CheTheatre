<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EmployeeRepository extends EntityRepository
{
    public function findLimitEmployees($limit, $offset = 0)
    {
        $qb = $this->createQueryBuilder('u');
        $qb ->setFirstResult( $offset )
            ->setMaxResults( $limit );

        $query = $qb->getQuery();
        return $query->execute();

    }
}