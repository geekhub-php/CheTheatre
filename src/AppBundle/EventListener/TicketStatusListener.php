<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TicketStatusListener
{
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $ticket = $args->getEntity();

        if (!$ticket instanceof Ticket && !$args->hasChangedField('status')) {
            return;
        }
        
        $oldStatus = $args->getOldValue('status');
        $newStatus = $args->getNewValue('status');

        if (!in_array($newStatus, Ticket::getStatuses())) {
            throw new \InvalidArgumentException("Invalid ticket status");
        }

        if ($oldStatus === Ticket::STATUS_PAID) {
            throw new TicketStatusConflictException("Invalid status. Ticket already paid.");
        }
    }
}
