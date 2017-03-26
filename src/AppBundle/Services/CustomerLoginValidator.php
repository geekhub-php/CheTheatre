<?php

namespace AppBundle\Services;

use AppBundle\Form\Type\CustomerType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\Customer;

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
     * @var GuzzleClientFacebook
     */
    private $facebookGuzzle;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $apiKeyInHeader;

    /**
     * @param ManagerRegistry $registry
     * @param FormFactoryInterface $formFactory
     * @param GuzzleClientFacebook $facebookSdk
     */
    public function __construct(ManagerRegistry $registry, FormFactoryInterface $formFactory, GuzzleClientFacebook $facebookGuzzle)
    {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->facebookGuzzle = $facebookGuzzle;
    }

    /**
     * @param $userAuthenticated
     * @param array $data
     * @param string $apiKeyInHeader
     * @return Customer|null|object|\Symfony\Component\Form\FormInterface
     * @throws \Exception
     */
    public function resultOptions($userAuthenticated, $data, $apiKeyInHeader)
    {
        $this->data = $data;
        $this->apiKeyInHeader = $apiKeyInHeader;

        $form = $this->formFactory->create(CustomerType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        if (!$userAuthenticated) {
            if ($apiKeyInHeader) {
                throw new \Exception('403 Invalid API-Key-Token');
            }

            return $this->newCustomer();
        }

        if ($data['email'] && $data['firstName'] && $data['lastName']) {
            return $this->loginForm();
        }

        if ($data['socialNetwork'] == 'facebook' && $data['socialToken']) {
            return $this->loginFacebook();
        }

        throw new \Exception('401 Invalid credentials');
    }

    /**
     * @return Customer
     */
    private function newCustomer()
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
     * @return Customer|null|object
     */
    private function loginForm()
    {
        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $this->apiKeyInHeader]);
        $customer->setFirstName($this->data['firstName']);
        $customer->setLastName($this->data['lastName']);
        $customer->setEmail($this->data['email']);
        $this->registry->getManager()->flush();

        return $customer;
    }

    /**
     * @return Customer|null|object
     */
    private function loginFacebook()
    {
        $userFacebook = $this->facebookGuzzle
            ->getUserFacebook($this->data['socialToken']);

        $customer = $this->registry->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $this->apiKeyInHeader]);
        $customer->setEmail($userFacebook->email);
        $customer->setFacebookId($userFacebook->id);
        $customer->setFirstName($userFacebook->first_name);
        $customer->setLastName($userFacebook->last_name);
        $this->registry->getManager()->flush();

        return $customer;
    }
}
