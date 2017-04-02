<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
use AppBundle\Repository\CustomerOrderRepository;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
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

        if ($oldStatus === Ticket::STATUS_OFFLINE) {
            return;
        }

        //$user = $this->tokenStorage->getToken()->getUser();
        $user = $em->getRepository('AppBundle:Customer')->find(1);
        /** @var CustomerOrderRepository $customerOrderRepository */
        $customerOrderRepository = $em->getRepository('AppBundle:CustomerOrder');
        $order = $customerOrderRepository->findLastOpenOrder($user);

        /**
         * Creating order if isn't exist
         */
        if (!$order) {
            $order = new CustomerOrder($user);
            $ticketMetadata = $em->getClassMetadata(CustomerOrder::class);
            $em->persist($order);
            $uow->computeChangeSet($ticketMetadata, $order);
        }

        /**
         * Set an order to a ticket
         *
         */
        if ($oldStatus === Ticket::STATUS_FREE && $newStatus === Ticket::STATUS_BOOKED) {
            $ticket->setCustomerOrder($order);
            /**
             * Unset an order from a ticket
             */
        } elseif ($oldStatus === Ticket::STATUS_BOOKED && $newStatus === Ticket::STATUS_FREE) {
            $ticket->setCustomerOrder(null);
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
