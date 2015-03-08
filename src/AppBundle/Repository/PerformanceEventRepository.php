<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PerformanceEventRepository extends EntityRepository
{
    public function findByDateRangeAndSlug(\DateTime $fromDate, \DateTime $toDate, $performanceSlug = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->WHERE('u.dateTime BETWEEN :from AND :to')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->orderBy('u.dateTime', 'ASC')
        ;

        if ($performanceSlug) {
            $qb->join('u.performance', 'p')->andWhere('p.slug = :slug')->setParameter('slug', $performanceSlug);
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
