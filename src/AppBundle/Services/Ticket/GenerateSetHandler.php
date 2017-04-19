<?php

namespace AppBundle\Services\Ticket;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\VenueSector;
use AppBundle\Repository\PriceCategoryRepository;
use AppBundle\Repository\SeatRepository;
use AppBundle\Repository\VenueSectorRepository;
use AppBundle\Services\PriceCategory\GetSeatsHandler;

class GenerateSetHandler
{
    /** @var GetSeatsHandler */
    private $priceCategoryGetSeats;

    /** @var SeatRepository */
    private $seatRepository;

    /** @var VenueSectorRepository */
    private $venueSectorRepository;

    /** @var PriceCategoryRepository */
    private $priceCategoryRepository;

    /**
     * @param SeatRepository $seatRepository
     * @param VenueSectorRepository $venueSectorRepository
     * @param PriceCategoryRepository $priceCategoryRepository
     * @param GetSeatsHandler $priceCategoryGetSeats
     */
    public function __construct(
        GetSeatsHandler $priceCategoryGetSeats,
        SeatRepository $seatRepository,
        VenueSectorRepository $venueSectorRepository,
        PriceCategoryRepository $priceCategoryRepository
    ) {
        $this->priceCategoryGetSeats = $priceCategoryGetSeats;
        $this->seatRepository = $seatRepository;
        $this->venueSectorRepository = $venueSectorRepository;
        $this->priceCategoryRepository = $priceCategoryRepository;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @return Ticket[]
     * @throws \Exception
     */
    public function handle(PerformanceEvent $performanceEvent): array
    {
        return $this->generateSetForPerformanceEvent($performanceEvent);
    }

    /**
     * @param PerformanceEvent $performanceEvent
     *
     * @return Ticket[]
     */
    protected function generateSetForPerformanceEvent(PerformanceEvent $performanceEvent): array
    {
        $venueSectors = $this->venueSectorRepository->getByVenue($performanceEvent->getVenue());
        $tickets = [];
        foreach ($venueSectors as $venueSector) {
            $tickets = array_merge(
                $tickets,
                $this->generateSetForPerformanceEventAndVenueSector($performanceEvent, $venueSector)
            );
        }
        $this->validateTickets($performanceEvent, $tickets);

        // TODO Logging for generated ticket

        return $tickets;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @param VenueSector $venueSectors
     *
     * @return Ticket[]
     */
    protected function generateSetForPerformanceEventAndVenueSector(
        PerformanceEvent $performanceEvent,
        VenueSector $venueSectors
    ): array {
        $tickets = [];

        $priceCategories = $this->priceCategoryRepository
            ->getByPerformanceEventAndVenueSector($performanceEvent, $venueSectors);

        $seats = [];
        foreach ($priceCategories as $priceCategory) {
            $priceCategorySeats = $this->priceCategoryGetSeats->handle($priceCategory);
            $seats =  array_merge(
                $seats,
                $priceCategorySeats
            );

            /** @var Seat $seat */
            foreach ($seats as $seat) {
                $tickets[] = new Ticket(
                    $seat,
                    $performanceEvent,
                    $priceCategory,
                    $priceCategory->getPrice(),
                    $performanceEvent->getSeriesDate(),
                    $performanceEvent->getSeriesNumber()
                );
            }
        };

        $this->validateSeatsDuplicates($venueSectors, $seats);
        $this->validateSeatsNumberForVenueSector($venueSectors, $seats);

        return $tickets;
    }

    /**
     * @param VenueSector $venueSectors
     * @param Seat[] $seats
     * @throws \Exception
     */
    protected function validateSeatsNumberForVenueSector(VenueSector $venueSectors, array $seats)
    {
        $venueSectorSeats = $this->seatRepository->getByVenueSector($venueSectors);
        if (count($venueSectorSeats) <> count($seats)) {
            throw new \Exception(
                sprintf(
                    'For %s number of seats: %s but should be: %s',
                    $venueSectors,
                    count($seats),
                    count($venueSectorSeats)
                )
            );
        }
    }

    /**
     * @param VenueSector $venueSectors
     * @param Seat[] $seats
     * @throws \Exception
     */
    protected function validateSeatsDuplicates(VenueSector $venueSectors, array $seats)
    {
        if (count(array_unique($seats)) < count($seats)) {
            throw new \Exception(
                sprintf('For %s PriceCategories arranged incorrectly. Duplicates appears.', $venueSectors)
            );
        }
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @param Ticket[] $ticket
     * @throws \Exception
     */
    protected function validateTickets(PerformanceEvent $performanceEvent, array $ticket)
    {
        $venueSeats = $this->seatRepository->getByVenue($performanceEvent->getVenue());
        if (count($venueSeats) <> count($ticket)) {
            throw new \Exception(
                sprintf(
                    'For %s number of tickets not equal to seats number. Seats: %s. Tickets: %s.',
                    $performanceEvent,
                    count($venueSeats),
                    count($ticket)
                )
            );
        }
    }
}
