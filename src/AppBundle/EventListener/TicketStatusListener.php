<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TicketStatusListener
{
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Ticket) {
                return;
            }

            if (!key_exists('status', $uow->getEntityChangeSet($entity))) {
                return;
            }

            $ticket = $entity;
        }

        $oldStatus = $uow->getEntityChangeSet($ticket)['status'][0];
        $newStatus = $uow->getEntityChangeSet($ticket)['status'][1];

        if (!in_array($newStatus, Ticket::getStatuses())) {
            throw new \InvalidArgumentException("Invalid ticket status");
        }

        if ($oldStatus === Ticket::STATUS_PAID) {
            throw new TicketStatusConflictException("Invalid status. Ticket already paid.");
        }

        $user = $this->tokenStorage->getToken()->getUser();

        /** @var CustomerOrder $order */
        $order = $em->getRepository('AppBundle:CustomerOrder')->findOpenedCustomerOrder($user);

        /**
         * Creating order if isn't exist
         */
        if (!$order) {
            $order = new CustomerOrder($user);
            $ticketMetadata = $em->getClassMetadata(CustomerOrder::class);
            $em->persist($order);
            $uow->computeChangeSet($ticketMetadata, $order);
        }

        if ($oldStatus === Ticket::STATUS_FREE && $newStatus === Ticket::STATUS_BOOKED) {
            $ticket->setCustomerOrder($order);
        } elseif ($oldStatus === Ticket::STATUS_BOOKED && $newStatus === Ticket::STATUS_FREE) {
            $ticket->setCustomerOrder(null);
        }
    }
}
