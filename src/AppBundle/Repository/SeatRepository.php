<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Seat;
use AppBundle\Entity\Venue;
use AppBundle\Entity\VenueSector;
use AppBundle\Exception\NotFoundException;

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

    /**
     * Get Seats for specific Venue
     *
     * @param Venue $venue
     *
     * @return Seat[]
     * @throws NotFoundException
     */
    public function getByVenue(Venue $venue): array
    {
        $seats = $this->findByVenue($venue);

        if (empty($seats)) {
            throw new NotFoundException('No seats found for Venue ID: '.$venue->getId());
        }

        return $seats;
    }

    /**
     * Get Seats for specific Venue
     *
     * @param VenueSector $venueSector
     *
     * @return Seat[]
     * @throws NotFoundException
     */
    public function getByVenueSector(VenueSector $venueSector): array
    {
        $seats = $this->findBy([
            'venueSector' => $venueSector
        ]);

        if (empty($seats)) {
            throw new NotFoundException(
                sprintf('No seats found for venue sector: %s', $venueSector)
            );
        }

        return $seats;
    }


    /**
     * Get Seats for specific Venue
     *
     * @param VenueSector $venueSector
     * @param int $row
     *
     * @return Seat[]
     * @throws NotFoundException
     */
    public function getByVenueSectorAndRow(VenueSector $venueSector, int $row): array
    {
        $seats = $this->findBy([
            'venueSector' => $venueSector,
            'row' => $row
        ]);

        if (empty($seats)) {
            throw new NotFoundException(
                sprintf('No seats found for venue sector: %s row: %s ', $venueSector, $row)
            );
        }

        return $seats;
    }

    /**
     * Get Seats for specific Venue
     *
     * @param VenueSector $venueSector
     * @param int $row
     * @param int $place
     *
     * @return Seat
     * @throws NotFoundException
     */
    public function getByVenueSectorRowAndPlace(VenueSector $venueSector, int $row, int $place): Seat
    {
        /** @var Seat $seat */
        $seat = $this->findOneBy([
            'venueSector' => $venueSector,
            'row' => $row,
            'place' => $place,
        ]);

        if (empty($seat)) {
            throw new NotFoundException(
                sprintf('No Seat found for venue sector: %s, row: %s, place %s', $venueSector, $row, $place)
            );
        }

        return $seat;
    }
}
