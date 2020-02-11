<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->select($qb->expr()->count('u'))->getQuery();

        return $query->getSingleScalarResult();
    }
}
