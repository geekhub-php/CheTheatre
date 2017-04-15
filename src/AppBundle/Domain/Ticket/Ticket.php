<?php

namespace AppBundle\Domain\Ticket;

use AppBundle\Domain\Seat\SeatInterface;
use AppBundle\Entity\PerformanceEvent as PerformanceEventEntity;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\Seat as SeatEntity;
use AppBundle\Entity\Ticket as TicketEntity;
use AppBundle\Exception\NotFoundException;
use AppBundle\Repository\TicketRepository;
use Symfony\Component\Security\Acl\Exception\Exception;

class Ticket implements TicketInterface
{
    /** @var TicketRepository */
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
    public function generateSet(PerformanceEventEntity $performanceEventEntity, bool $force = false)
    {
        $seats = $this->seatService->getByVenue($performanceEventEntity->getVenue());

        $priceCategories = $performanceEventEntity->getPriceCategories();

        if (!$priceCategories->count()) {
            throw new \Exception('No priceCategory found for performance event: '.$performanceEventEntity->getId());
        }

        // TODO
        // generate only if SET was not generated before
        // check PriceCategory, and take price from it;
        // get seriesNumber, seriesDate from PerformanceEvent.
        // FORCE ticket SET generation
        // Log for generated ticket

        $price = 50;
        $count = 0;
        $priceCategory = $priceCategories->first();

        $tickets = [];
        /** @var SeatEntity $seat */
        foreach ($seats as $seat) {
            $tickets[] = new TicketEntity(
                $seat,
                $performanceEventEntity,
                $priceCategory,
                $price,
                $performanceEventEntity->getSeriesDate(),
                $performanceEventEntity->getSeriesNumber()
            );
            $count += 1;
        }

        $this->ticketRepository->batchSave($tickets);

        return $count;
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
