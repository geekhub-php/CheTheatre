<?php

namespace AppBundle\Services;

use AppBundle\Model\CustomerResponse;
use Doctrine\Common\Persistence\ManagerRegistry;
use Gedmo\ReferenceIntegrity\Mapping\Validator;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class CustomerLoginValidator
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
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $apiKeyInHeader;

    /**
     * @param ManagerRegistry         $registry
     * @param FacebookUserProvider    $facebookUserProvider
     * @param RecursiveValidator      $validator
     */
    public function __construct(
        ManagerRegistry $registry,
        FacebookUserProvider $facebookUserProvider,
        RecursiveValidator $validator
    ) {
        $this->registry = $registry;
        $this->facebookUserProvider = $facebookUserProvider;
        $this->validator = $validator;
    }

    /**
     * @param mixed  $userAuthenticated
     * @param array  $data
     * @param string $apiKeyInHeader
     *
     * @return CustomerResponse
     *
     * @throws \Exception
     */
    public function resultOptions($userAuthenticated, $data, $apiKeyInHeader)
    {
        $this->data = $data;
        $this->apiKeyInHeader = $apiKeyInHeader;

        if (!$userAuthenticated) {
            if ($apiKeyInHeader) {
                throw new HttpException(401, 'Invalid API-Key-Token');
            }

            return $this->newCustomer();
        }

        if (!($this->validInputData($this->data))) {
            throw new HttpException(400, 'Validation error');
        }

        if ($data['email'] && $data['firstName'] && $data['lastName']) {
            return $this->loginForm();
        }

        if ($data['socialNetwork'] == 'facebook' && $data['socialToken']) {
            return $this->loginFacebook();
        }

        throw new HttpException(401, 'Invalid credentials');
    }

    /**
     * @return CustomerResponse
     */
    private function newCustomer()
    {
        $apiKey = uniqid('token_');

        $customer = new Customer();
        $customer->setUsername('customer');
        $customer->setApiKey($apiKey);
        $this->registry->getManager()->persist($customer);
        $this->registry->getManager()->flush();

        $customerResponse = new CustomerResponse($customer);

        return $customerResponse;
    }

    /**
     * @return CustomerResponse
     */
    private function loginForm()
    {
        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $this->apiKeyInHeader]);
        $customer->setFirstName($this->data['firstName']);
        $customer->setLastName($this->data['lastName']);
        $customer->setEmail($this->data['email']);
        $this->registry->getManager()->flush();

        $customerResponse = new CustomerResponse($customer);

        return $customerResponse;
    }

    /**
     * @return CustomerResponse
     */
    private function loginFacebook()
    {
        $userFacebook = $this->facebookUserProvider
            ->getUser($this->data['socialToken']);

        $customerFacebook = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['facebookId' => $userFacebook->id]);
        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $this->apiKeyInHeader]);

        if (!$customerFacebook) {
            $customer->setEmail($userFacebook->email);
            $customer->setFacebookId($userFacebook->id);
            $customer->setFirstName($userFacebook->first_name);
            $customer->setLastName($userFacebook->last_name);
            $this->registry->getManager()->flush();

            $customerResponse = new CustomerResponse($customer);

            return $customerResponse;
        }

        $this->registry->getManager()->remove($customer);
        $customerFacebook->setApiKey($this->apiKeyInHeader);
        $this->registry->getManager()->flush();

        $customerResponse = new CustomerResponse($customerFacebook);

        return $customerResponse;
    }

    /**
     * @return bool
     */
    private function validInputData($data)
    {
        $customer = new Customer();
        $customer->setFirstName($data['firstName']);
        $customer->setLastName($data['lastName']);
        $customer->setEmail($data['email']);
        $errors = $this->validator->validate($customer);

        if (count($errors) > 0) {
            return false;
        }

        return true;
    }
}
