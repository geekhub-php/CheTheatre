<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->select($qb->expr()->count('u'))->getQuery();

        return $query->getSingleScalarResult();
    }
}
