<?php

namespace AppBundle\Repository;

use AppBundle\Domain\Ticket\Ticket;

class TicketRepository extends AbstractRepository
{
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
}
