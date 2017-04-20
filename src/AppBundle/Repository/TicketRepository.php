<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PerformanceEvent;
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
}
