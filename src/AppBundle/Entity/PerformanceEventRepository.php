<?php

namespace AppBundle\Entity;

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
}
