<?php

namespace AppBundle\Services;

use AppBundle\Model\CustomerRequest;
use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\Customer;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @param RecursiveValidator   $validator
     * @param Serializer           $serializer
     * @param FacebookUserProvider $facebookUserProvider
     */
    public function __construct(
        ManagerRegistry $registry,
        RecursiveValidator $validator,
        Serializer $serializer,
        FacebookUserProvider $facebookUserProvider
    ) {
        $this->registry = $registry;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->facebookUserProvider = $facebookUserProvider;
    }

    /**
     * @return Customer
     */
    public function newCustomer()
    {
        do {
            $apiKey = uniqid('token_');
            $customer = new Customer();
            $customer->setUsername('customer');
            $customer->setApiKey($apiKey);
            dump($apiKey);
        } while (!$this->customerValidator($customer, 'uniqApikey'));

        $this->registry->getManager()->persist($customer);
        $this->registry->getManager()->flush();

        return $customer;
    }

    /**
     * @param string $apiKey
     * @param array  $content
     *
     * @return Customer
     */
    public function updateCustomer($apiKey, $content)
    {
        $serializer = $this->serializer;
        $user = $serializer->deserialize($content, CustomerRequest::class, 'json');
        if ($this->customerValidator($user, 'update')) {
            $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKey]);
            $customer->setApiKey($apiKey);
            $customer->setFirstName($user->getFirstName());
            $customer->setLastName($user->getLastName());
            $customer->setEmail($user->getEmail());
            $this->registry->getManager()->flush();

            return $customer;
        }
        throw new HttpException(400, 'Validation error');
    }

    /**
     * @param string $apiKey
     * @param array  $content
     *
     * @return Customer
     */
    public function loginSocialNetwork($apiKey, $content)
    {
        $customer = $this->serializer->deserialize(
            $content,
            CustomerRequest::class,
            'json'
        );

        if ($this->customerValidator($customer, 'socialNetwork')) {
            $userSocial = $this->facebookUserProvider
                ->getUser($customer->getSocialToken());

            $customerFacebook = $this->registry->getRepository('AppBundle:Customer')
                ->findOneBy(['facebookId' => $userSocial->getId()]);
            $customer = $this->registry->getRepository('AppBundle:Customer')
                ->findOneBy(['apiKey' => $apiKey]);

            if (!$customerFacebook) {
                $customer->setFacebookId($userSocial->getId());
                $customer->setEmail($userSocial->getEmail());
                $customer->setFirstName($userSocial->getFirstName());
                $customer->setLastName($userSocial->getLastName());
                $this->registry->getManager()->flush();

                return $customer;
            }

            $this->registry->getManager()->remove($customer);
            $customerFacebook->setApiKey($apiKey);
            $this->registry->getManager()->flush();

            return $customerFacebook;
        }
        throw new HttpException(400, 'Validation error');
    }
    /**
     * @return bool
     */
    private function customerValidator($customer, $groups)
    {
        $errors = $this->validator->validate($customer, $groups);

        if (count($errors) > 0) {
            return false;
        }

        return true;
    }
}
