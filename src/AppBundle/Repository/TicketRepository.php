<?php

namespace AppBundle\Repository;

class TicketRepository extends AbstractRepository
{
    /**
     * @param array $tickets
     */
    public function batchSave(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $this->save($ticket, false);
        }
        $this->getEntityManager()->flush();
    }
}
