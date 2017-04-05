<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

class TicketStatusListener
{
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        if (!$ticket = $this->checkTicketStatusUpdate($uow)) {
            return;
        }

        $oldStatus = $uow->getEntityChangeSet($ticket)['status'][0];
        $newStatus = $uow->getEntityChangeSet($ticket)['status'][1];

        if (!in_array($newStatus, Ticket::getStatuses())) {
            throw new \InvalidArgumentException("Invalid ticket status");
        }

        if ($oldStatus === Ticket::STATUS_PAID) {
            throw new TicketStatusConflictException("Invalid status. Ticket already paid.");
        }
    }

    /**
     * Checks if ticket status was updated
     *
     * @param UnitOfWork $uow
     * @return Ticket|false
     */
    private function checkTicketStatusUpdate(UnitOfWork $uow)
    {
        if (!$uow->getScheduledEntityUpdates()) {
            return false;
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Ticket && !key_exists('status', $uow->getEntityChangeSet($entity))) {
                return false;
            }
            return $entity;
        }
    }
}
