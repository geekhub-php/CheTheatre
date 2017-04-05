<?php

namespace AppBundle\Services;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Repository\CustomerOrderRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderManager
{
    protected $doctrine;

    protected $tokenStorage;

    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public function addOrderToTicket(Ticket $ticket)
    {
        $order = $this->getCustomerOrder();
        $ticket->setCustomerOrder($order);
    }

    public function removeOrderFromTicket(Ticket $ticket)
    {
        $ticket->setCustomerOrder(null);
    }

    private function getCustomerOrder()
    {
        $em = $this->doctrine->getEntityManager();
        /** @var CustomerOrderRepository $repository */
        $customerOrderRepository = $em->getRepository('AppBundle:CustomerOrder');
        //$customer = $this->tokenStorage->getToken()->getUser();
        $customer = $em->getRepository('AppBundle:Customer')->findOneBy([]);
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
