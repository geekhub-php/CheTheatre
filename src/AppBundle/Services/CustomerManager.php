<?php

namespace AppBundle\Services;

use AppBundle\Entity\Customer;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomerManager
{
    protected $requestStack;

    protected $doctrine;

    public function __construct(RequestStack $requestStack, RegistryInterface $doctrine)
    {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
    }

    /**
     * @return Customer
     */
    public function getCurrentUserByApiKey(): Customer
    {
        $em = $this->doctrine->getEntityManager();
        $apiKey = $this->requestStack->getCurrentRequest()->headers->get('API-Key-Token');
        $customer = $em
            ->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKey]);

        return $customer;
    }
}
