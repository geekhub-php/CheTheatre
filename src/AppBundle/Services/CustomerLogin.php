<?php

namespace AppBundle\Services;

use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\Customer;
use Gedmo\ReferenceIntegrity\Mapping\Validator;
use JMS\Serializer\Serializer;
use Symfony\Component\Validator\Validator\RecursiveValidator;

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
     * @var RecursiveValidator
     */
    private $validator;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param ManagerRegistry      $registry
     * @param FacebookUserProvider $facebookUserProvider
     */
    public function __construct(
        ManagerRegistry $registry,
        RecursiveValidator $validator,
        Serializer $serializer
        //  FacebookUserProvider $facebookUserProvider
)
    {
        $this->registry = $registry;
        $this->validator = $validator;
        $this->serializer = $serializer;
        //$this->facebookUserProvider = $facebookUserProvider;
    }

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

    public function updateCustomer($apiKey, $content)
    {
        $serializer = $this->serializer;
        $user = $serializer->deserialize($content, 'AppBundle\Model\UpdateCustomerRequest', 'json');
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return null;
        }

        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKey]);
        $customer->setApiKey($apiKey);
        $customer->setFirstName($user->getFirstName());
        $customer->setLastName($user->getLastName());
        $customer->setEmail($user->getEmail());
        $this->registry->getManager()->flush();

        return $customer;
    }
}
