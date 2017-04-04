<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
use AppBundle\Repository\CustomerOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $order = $this->getCustomerOrder($em, $uow);

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

    /**
     * Gets order or creating if doesn't exist
     *
     * @param EntityManagerInterface $em
     * @param UnitOfWork $uow
     * @return CustomerOrder $order
     */
    private function getCustomerOrder(EntityManagerInterface $em, UnitOfWork $uow): CustomerOrder
    {
        /** @var CustomerOrderRepository $repository */
        $customerOrderRepository = $em->getRepository('AppBundle:CustomerOrder');
        //$customer = $this->tokenStorage->getToken()->getUser();
        $customer = $em->getRepository('AppBundle:Customer')->find(1);
        $order = $customerOrderRepository->findLastOpenOrder($customer);
        /**
         * Creating order if isn't exist
         */
        if (!$order) {
            $order = new CustomerOrder($customer);
            $ticketMetadata = $em->getClassMetadata(CustomerOrder::class);
            $em->persist($order);
            $uow->computeChangeSet($ticketMetadata, $order);
        }

        return $order;
    }

}
