<?php

namespace AppBundle\Domain\Seat;

use AppBundle\Entity\Venue;
use AppBundle\Exception\NotFoundException;
use AppBundle\Entity\Seat as SeatEntity;

interface SeatInterface
{
    /**
     * Get PerformanceEvent by ID
     *
     * @param Venue $venue
     *
     * @return SeatEntity[]
     * @throws NotFoundException
     */
    public function getByVenue(Venue $venue): array;
}
