<?php

namespace AppBundle\Services;

use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\Customer;

class CustomerLogin
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var FacebookUserProvider
     */
    private $facebookUserProvider;

    /**
     * @param ManagerRegistry      $registry
     * @param FacebookUserProvider $facebookUserProvider
     */
    public function __construct(
        ManagerRegistry $registry,
        FacebookUserProvider $facebookUserProvider
    ) {
        $this->registry = $registry;
        $this->facebookUserProvider = $facebookUserProvider;
    }

    /**
     * @return Customer
     */
    public function newCustomer()
    {
        $apiKey = uniqid('token_');

        $customer = new Customer();
        $customer->setUsername('customer');
        $customer->setApiKey($apiKey);
        $this->registry->getManager()->persist($customer);
        $this->registry->getManager()->flush();

        return $customer;
    }

    /**
     * @param string $socialNetwork
     * @param string $socialToken
     * @param string $apiKeyInHeader
     *
     * @return Customer
     */
    public function loginSocialNetwork($socialNetwork, $socialToken, $apiKeyInHeader)
    {
        if ($socialNetwork == 'facebook') {
            $socialNetwork = $this->facebookUserProvider;
        }

        $userFacebook = $socialNetwork
            ->getUser($socialToken);

        $customerFacebook = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['facebookId' => $userFacebook->getId()]);
        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKeyInHeader]);

        if (!$customerFacebook) {
            $customer->setFacebookId($userFacebook->getId());
            $customer->setEmail($userFacebook->getEmail());
            $customer->setFirstName($userFacebook->getFirstName());
            $customer->setLastName($userFacebook->getLastName());
            $this->registry->getManager()->flush();

            return $customer;
        }

        $this->registry->getManager()->remove($customer);
        $customerFacebook->setApiKey($apiKeyInHeader);
        $this->registry->getManager()->flush();

        return $customerFacebook;
    }
}
