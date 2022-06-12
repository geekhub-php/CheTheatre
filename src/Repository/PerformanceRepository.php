<?php

namespace App\Repository;

use App\Entity\Performance;
use App\Entity\RepertoireSeason;
use Doctrine\Persistence\ManagerRegistry;

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
            ->orderBy('p.premiere', 'DESC')
            ->getQuery()
            ->enableResultCache(self::CACHE_TTL)
            ->execute();
    }

    /**
     * @return array|Performance[]
     */
    public function findAllWithinSeasonsExcept(RepertoireSeason $season): array
    {
        $ids = $season->getPerformances()
            ->map(fn (Performance $performance) => $performance->getId())
            ->toArray();

        $qb = $this->createQueryBuilder('p');

        $qb->innerJoin('p.seasons', 'ps')
            ->groupBy('p.id')
            ->orderBy('p.premiere', 'DESC');

        if (!empty($ids)) {
            $qb->where($qb->expr()->notIn('p.id', $ids));
        }

        return $qb
            ->getQuery()
            ->enableResultCache(self::CACHE_TTL)
            ->getResult()
        ;
    }
}
