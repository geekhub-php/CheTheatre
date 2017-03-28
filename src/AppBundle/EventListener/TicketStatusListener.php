<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;;
use AppBundle\Exception\TicketStatusConflictException;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TicketStatusListener
{
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        /** @var Ticket $entity */
        $entity = $args->getEntity();
        if (!$entity instanceof Ticket) {
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

        $em = $args->getEntityManager();
        $user = $em->getRepository('AppBundle:Customer')->find(1); //for testing
        //$user = $this->tokenStorage->getToken()->getUser();
        /** @var CustomerOrder $order */
        $order = $em->getRepository('AppBundle:CustomerOrder')->findOpenedCustomerOrder($user);

        if ($oldStatus === Ticket::STATUS_FREE && $newStatus === Ticket::STATUS_BOOKED) {
            $entity->setCustomerOrder($order);
        } elseif ($oldStatus === Ticket::STATUS_BOOKED && $newStatus === Ticket::STATUS_FREE) {
            $entity->setCustomerOrder(null);
        }

    }
}