<?php

namespace AppBundle\Repository;

class PerformanceRepository extends AbstractRepository
{
    public function getRepertoryCount()
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb
            ->select($qb->expr()->count('u'))
            ->where('u.festival is NULL')
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
