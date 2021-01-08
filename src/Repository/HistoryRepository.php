<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Common\Persistence\ManagerRegistry;

class HistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function findAllHistory($limit, $page)
    {
        $qb = $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->orderBy('u.dateTime', 'DESC')
        ;

        $query = $qb->getQuery()->enableResultCache(self::CACHE_TTL);

        return $query->execute();
    }

    public function getCount()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select($qb->expr()->count('u'));

        $query = $qb->getQuery();

        $query->useResultCache(true, 3600);

        return $query->getSingleScalarResult();
    }
}
