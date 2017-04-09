<?php

namespace AppBundle\Services;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Repository\CustomerOrderRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrderManager
{
    protected $doctrine;

    protected $customerManager;

    public function __construct(RegistryInterface $doctrine, CustomerManager $customerManager)
    {
        $this->doctrine = $doctrine;
        $this->customerManager = $customerManager;
    }

    public function addOrderToTicket(Ticket $ticket)
    {
        $order = $this->getCustomerOrder();
        $ticket->setCustomerOrder($order);
    }

    public function removeOrderFromTicket(Ticket $ticket)
    {
        $order = $this->getCustomerOrder();
        if ($order === $ticket->getCustomerOrder()) {
            $ticket->setCustomerOrder(null);
        }
    }

    private function getCustomerOrder(): CustomerOrder
    {
        $em = $this->doctrine->getEntityManager();
        $customer = $this->customerManager->getCurrentUserByApiKey();
        /** @var CustomerOrderRepository $repository */
        $customerOrderRepository = $em->getRepository('AppBundle:CustomerOrder');
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
