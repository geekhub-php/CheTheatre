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

    /**
     * @param PerformanceEvent $performanceEvent
     * @return Query
     */
    public function findVenueSectorsByPerformanceEventQuery(PerformanceEvent $performanceEvent)
    {
        return $this->findVenueSectorsByPerformanceEventQueryBuilder($performanceEvent)->getQuery();
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @return array
     */
    public function findVenueSectorsByPerformanceEvent(PerformanceEvent $performanceEvent)
    {
        return $this->findVenueSectorsByPerformanceEventQuery($performanceEvent)->getResult();
    }
}
