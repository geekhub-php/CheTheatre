<?php

namespace AppBundle\Domain\Seat;

use AppBundle\Entity\Venue;
use AppBundle\Exception\NotFoundException;
use AppBundle\Entity\Seat as SeatEntity;
use AppBundle\Repository\SeatRepository;

class Seat implements SeatInterface
{
    /** @var SeatRepository */
    private $seatRepository;

    /**
     * Domain Seat constructor.
     *
     * @param SeatRepository $seatRepository
     */
    public function __construct(
        SeatRepository $seatRepository
    ) {
        $this->seatRepository = $seatRepository;
    }

    /**
     * @inheritdoc
     */
    public function getByVenue(Venue $venue): array
    {
        /** @var SeatEntity[] $seats */
        $seats = $this->seatRepository->findByVenue($venue);
        if (empty($seats)) {
            throw new NotFoundException('Seats not found by venue: '.$venue->getTitle());
        }

        return $seats;
    }
}
