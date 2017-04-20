<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\Ticket\NotRemovableSetException;

class TicketRepository extends AbstractRepository
{

    /**
     * @param PerformanceEvent $performanceEvent
     *
     * @return Ticket[]
     * @throws NotRemovableSetException
     */
    public function getRemovableTicketSet(PerformanceEvent $performanceEvent): array
    {
        $ticket = $this->findOneBy([
            'performanceEvent' => $performanceEvent,
            'status' => [Ticket::STATUS_BOOKED, Ticket::STATUS_PAID]
        ]);

        if (!empty($ticket)) {
            throw new NotRemovableSetException(
                sprintf(
                    'Impossible to remove tickets for PerformanceEvent: %s.',
                    $performanceEvent
                )
            );
        }

        $tickets = $this->findBy([
            'performanceEvent' => $performanceEvent
        ]);

        return $tickets;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     *
     * @return bool
     */
    public function isGeneratedSet(PerformanceEvent $performanceEvent)
    {
        $ticket = $this->findOneBy([
            'performanceEvent' => $performanceEvent
        ]);
        return !empty($ticket);
    }

    /**
     * @param Ticket[] $tickets
     */
    public function batchSave(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $this->save($ticket, false);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param Ticket[] $tickets
     */
    public function batchRemove(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $this->remove($ticket, false);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * Change Status in Ticket
     * form STATUS_OFFLINE to STATUS_FREE if Ticket is in RowsForSale
     * and vice versa
     *
     * @param PerformanceEvent $performanceEvent
     * @return int
     */
    public function enableTicketsForSale(PerformanceEvent $performanceEvent)
    {
        $seats = $this->getEntityManager()->getRepository(Seat::class)->getByVenue($performanceEvent->getVenue());
        foreach ($seats as $seat) {
            $tickets[] = self::changeStatusInTicket(
                $performanceEvent,
                $seat,
                Ticket::STATUS_FREE,
                Ticket::STATUS_OFFLINE
            );
        }

        $tickets = [];

        foreach ($performanceEvent->getRowsForSale() as $forSale) {
            $seats = $this
                ->getEntityManager()
                ->getRepository(Seat::class)
                ->getByVenueSectorAndRow(
                    $forSale->getVenueSector(),
                    $forSale->getRow()
                );
            foreach ($seats as $seat) {
                $tickets[] = self::changeStatusInTicket(
                    $performanceEvent,
                    $seat,
                    Ticket::STATUS_OFFLINE,
                    Ticket::STATUS_FREE
                );
            }
        }

        $count = count($tickets);
        return $count;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @param Seat $seat
     * @param string $oldStatus
     * @param string $newStatus
     * @return object
     */
    public function changeStatusInTicket(PerformanceEvent $performanceEvent, Seat $seat, $oldStatus, $newStatus)
    {
        $ticket = $this->findOneBy([
            'performanceEvent' => $performanceEvent,
            'seat' => $seat,
            'status' => $oldStatus,
        ]);
        if ($ticket) {
            $ticket->setStatus($newStatus);
            $this->getEntityManager()->persist($ticket);

            $this->getEntityManager()->flush();
        }
        return $ticket;
    }
}
