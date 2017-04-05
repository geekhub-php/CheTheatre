<?php

namespace AppBundle\Domain\Ticket;

use AppBundle\Domain\Seat\SeatInterface;
use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Seat as SeatEntity;
use AppBundle\Entity\Ticket as TicketEntity;
use AppBundle\Exception\NotFoundException;
use AppBundle\Repository\PerformanceEventRepository;
use AppBundle\Repository\TicketRepository;

class Ticket implements TicketInterface
{
    /** @var PerformanceEventRepository */
    private $ticketRepository;

    /** @var SeatInterface */
    private $seatService;

    /**
     * Domain Ticket constructor.
     *
     * @param TicketRepository $ticketRepository
     * @param SeatInterface $seatService
     */
    public function __construct(
        TicketRepository $ticketRepository,
        SeatInterface $seatService
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->seatService = $seatService;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function generateSet(PerformanceEvent $performanceEvent, bool $force = false)
    {
        $seats = $this->seatService->getByVenue($performanceEvent->getVenue());

        // TODO
        // generate only if SET was not generated before
        // check PriceCategory, and take price from it;
        // get seriesNumber, seriesDate from PerformanceEvent.
        // FORCE ticket SET generation
        // Log for generated ticket

        $seriesNumber = 'testTicket';
        $seriesDate = new \DateTime('now');
        $price = 50;
        $count = 0;

        $tickets = [];
        /** @var SeatEntity $seat */
        foreach ($seats as $seat) {
            $tickets[] = new TicketEntity($seat, $performanceEvent, $price, $seriesDate, $seriesNumber);
            $count += 1;
        }

        $this->ticketRepository->batchSave($tickets);

        return 'successfully generated '.$count;
    }

    /**
     * @inheritdoc
     */
    public function getById(string $id): TicketEntity
    {
        /** @var TicketEntity $ticket */
        $ticket = $this->ticketRepository->find($id);
        if (!$ticket) {
            throw new NotFoundException('Ticket not found by ID: '.$id);
        }

        return $ticket;
    }
}
