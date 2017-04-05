<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Venue;

class SeatRepository extends AbstractRepository
{
    public function findByVenue(Venue $venue)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.venueSector', 'vs')
            ->andWhere('vs.venue = :venue')
            ->setParameter('venue', $venue->getId())
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }
}
