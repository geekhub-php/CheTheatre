<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PerformanceEvent;

class RowsForSaleRepository extends AbstractRepository
{
    public function findVenueSectorsByPerformanceEventQueryBuilder(PerformanceEvent $performanceEvent)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.venueSector', 'vs')
            ->andWhere('vs.venue = :venue')
            ->setParameter('venue', $performanceEvent->getVenue())
        ;
        return $qb;
    }
}
