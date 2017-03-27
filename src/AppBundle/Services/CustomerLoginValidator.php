<?php

namespace AppBundle\Services;

use AppBundle\Form\Type\CustomerType;
use AppBundle\Model\CustomerResponse;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomerLoginValidator
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $apiKeyInHeader;

    /**
     * @param ManagerRegistry      $registry
     * @param FormFactoryInterface $formFactory
     * @param GuzzleClient         $guzzleClient
     */
    public function __construct(
        ManagerRegistry $registry,
        FormFactoryInterface $formFactory,
        GuzzleClient $guzzleClient
    ) {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param mixed  $userAuthenticated
     * @param array  $data
     * @param string $apiKeyInHeader
     *
     * @return CustomerResponse|\Symfony\Component\Form\FormInterface
     *
     * @throws \Exception
     */
    public function resultOptions($userAuthenticated, $data, $apiKeyInHeader)
    {
        $this->data = $data;
        $this->apiKeyInHeader = $apiKeyInHeader;

        $form = $this->formFactory->create(CustomerType::class);
        $form->submit($data);

        if (!$userAuthenticated) {
            if ($apiKeyInHeader) {
                throw new HttpException(401, 'Invalid API-Key-Token');
            }

            return $this->newCustomer();
        }

        if (!$form->isValid()) {
            return $form;
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
        $userFacebook = $this->guzzleClient
            ->getUserFacebook($this->data['socialToken']);

        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $this->apiKeyInHeader]);
        $customer->setEmail($userFacebook->email);
        $customer->setFacebookId($userFacebook->id);
        $customer->setFirstName($userFacebook->first_name);
        $customer->setLastName($userFacebook->last_name);
        $this->registry->getManager()->flush();

        $customerResponse = new CustomerResponse($customer);

        return $customerResponse;
    }
}
