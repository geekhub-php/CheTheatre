<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PerformanceEventRepository extends EntityRepository
{
    public function findByDateRange(\DateTime $fromDate, \DateTime $toDate)
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->WHERE('u.dateTime BETWEEN :from AND :to')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->orderBy('u.dateTime', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findBySlugAndDateRange(\DateTime $fromDate, \DateTime $toDate, $slug)
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->join('u.performance', 'p')
            ->WHERE('u.dateTime BETWEEN :from AND :to')
            ->andWhere('p.slug = :slug')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->setParameter('slug', $slug)
            ->orderBy('u.dateTime', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
    }
}
