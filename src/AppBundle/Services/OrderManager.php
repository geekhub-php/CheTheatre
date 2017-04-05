<?php

namespace AppBundle\Services;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Repository\CustomerOrderRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderManager
{
    private $doctrine;

    private $tokenStorage;

    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public function removeOrderToTicket(Ticket $ticket)
    {
        $ticket->setCustomerOrder(null);
    }

    public function addOrderToTicket(Ticket $ticket)
    {
        $order = $this->getCustomerOrder();
        $ticket->setCustomerOrder($order);
    }

    /**
     * @return CustomerOrder
     */
    public function getCustomerOrder(): CustomerOrder
    {
        $em = $this->doctrine->getEntityManager();
        /** @var CustomerOrderRepository $customerOrderRepository */
        $customerOrderRepository = $em->getRepository('AppBundle:CustomerOrder');
        //$customer = $this->tokenStorage->getToken()->getUser();
        $customer = $em->getRepository('AppBundle:Customer')->find(1);
        $order = $customerOrderRepository->findLastOpenOrder($customer);
        /**
         * Creating order if isn't exist
         */
        if (!$order) {
            $order = new CustomerOrder($customer);
            $em->persist($order);
        }

        return $order;
    }
}
