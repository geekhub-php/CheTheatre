<?php

namespace AppBundle\Services;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Repository\CustomerOrderRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrderManager
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function addOrderToTicket(Ticket $ticket, $apiKey)
    {
        $order = $this->getCustomerOrder($apiKey);
        $ticket->setCustomerOrder($order);
    }

    public function removeOrderFromTicket(Ticket $ticket)
    {
        $ticket->setCustomerOrder(null);
    }

    private function getCustomerOrder($apiKey): CustomerOrder
    {
        $em = $this->doctrine->getEntityManager();
        /** @var CustomerOrderRepository $repository */
        $customerOrderRepository = $em->getRepository('AppBundle:CustomerOrder');
        $customer = $em->getRepository('AppBundle:Customer')->findOneBy(['apiKey' => $apiKey]);
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
