<?php

namespace App\Repository;

use App\Entity\Performance;
use Doctrine\Common\Persistence\ManagerRegistry;

class PerformanceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Performance::class);
    }

    /**
     * @return array|Performance[]
     */
    public function findAllWithinSeasons()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.seasons', 'ps')
            ->groupBy('p.id')
            ->getQuery()
            ->enableResultCache(60*60*24)
            ->execute();
    }
}
