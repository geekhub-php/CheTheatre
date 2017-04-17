<?php

namespace AppBundle\Services;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\UserOrder;
use AppBundle\Repository\UserOrderRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        $ticket->setUserOrder($order);
    }

    public function removeOrderFromTicket(Ticket $ticket)
    {
        $em = $this->doctrine->getEntityManager();
        $user = $this->tokenStorage->getToken()->getUser();
        /** @var UserOrder $order */
        $order = $em->getRepository('AppBundle:UserOrder')->findLastOpenOrder($user);
        if ($ticket->getUserOrder() !== $order) {
            throw new AccessDeniedHttpException('You cannot change status for this ticket');
        }

        $ticket->setUserOrder(null);
        $ticket->setStatus(Ticket::STATUS_FREE);
    }

    /**
     * Returns or creates UserOrder
     *
     * @return UserOrder
     */
    private function getCustomerOrder()
    {
        $em = $this->doctrine->getEntityManager();
        $user = $this->tokenStorage->getToken()->getUser();
        /** @var UserOrderRepository $userOrderRepository */
        $userOrderRepository = $em->getRepository('AppBundle:UserOrder');
        $order = $userOrderRepository->findLastOpenOrder($user);

        /**
         * Creating order if isn't exist
         */
        if (!$order) {
            $order = new UserOrder($user);
            $em->persist($order);
        }

        return $order;
    }
}
