<?php

namespace App\Repository;

use App\Entity\PerformanceEvent;
use Doctrine\Common\Persistence\ManagerRegistry;

class PerformanceEventRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PerformanceEvent::class);
    }

    public function findByDateRangeAndSlug(\DateTime $fromDate, \DateTime $toDate, $performanceSlug = null, int $limit=null)
    {
        $toDate = clone $toDate;
        $toDate->setTime(23, 59,59);

        $qb = $this->createQueryBuilder('u')
            ->WHERE('u.dateTime BETWEEN :from AND :to')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->orderBy('u.dateTime', 'ASC')
        ;

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($performanceSlug) {
            $qb->join('u.performance', 'p')->andWhere('p.slug = :slug')->setParameter('slug', $performanceSlug);
        }

        $query = $qb->getQuery()->enableResultCache(60*60);

        return $query->execute();
    }
}
