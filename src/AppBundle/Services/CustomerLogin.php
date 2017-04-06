<?php

namespace AppBundle\Services;

use AppBundle\Model\CustomerRequest;
use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\Customer;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerLogin
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var FacebookUserProvider
     */
    private $facebookUserProvider;

    /**
     * @param ManagerRegistry      $registry
     * @param Serializer           $serializer
     * @param ValidatorInterface   $validator
     * @param FacebookUserProvider $facebookUserProvider
     */
    public function __construct(
        ManagerRegistry $registry,
        Serializer $serializer,
        ValidatorInterface $validator,
        FacebookUserProvider $facebookUserProvider
    ) {
        $this->registry = $registry;
        $this->serializer = $serializer;
        $this->validator = $validator;
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
     * @param string  $apiKey
     * @param string  $content
     *
     * @return Customer
     */
    public function updateCustomer($apiKey, $content)
    {
        $customerRequest = $this->serializer->deserialize(
            $content,
            CustomerRequest::class,
            'json'
        );

        $errors = $this->validator->validate($customerRequest, null, ['update']);

        if (count($errors) > 0) {
            throw new HttpException(400, 'Validation error');
        }

        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKey]);
        $customer->setApiKey($apiKey);
        $customer->setFirstName($customerRequest->getFirstName());
        $customer->setLastName($customerRequest->getLastName());
        $customer->setEmail($customerRequest->getEmail());

        $this->registry->getManager()->flush();

        return $customer;
    }

    /**
     * @param string  $apiKey
     * @param string  $content
     *
     * @return Customer
     */
    public function loginSocialNetwork($apiKey, $content)
    {
        $customerRequest = $this->serializer->deserialize(
            $content,
            CustomerRequest::class,
            'json'
        );

        $errors = $this->validator->validate($customerRequest, null, ['socialNetwork']);

        if (count($errors) > 0) {
            throw new HttpException(400, 'Validation error');
        }

        $userSocial = $this->facebookUserProvider
            ->getUser($customerRequest->getSocialToken());

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
}
