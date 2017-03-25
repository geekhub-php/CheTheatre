<?php

namespace AppBundle\Services;

use AppBundle\Form\Type\CustomerType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\Customer;

class ValidatorCustomersLoginOptions
{
    private $registry;
    public $facebookSdk;
    public function __construct(ManagerRegistry $registry, FormFactoryInterface $formFactory, FacebookSdk $facebookSdk)
    {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->FacebookSdk = $facebookSdk;
    }

    public function resultOptions($userAuthenticated, $data, $apiKeyInHeader)
    {
        $apiKey = uniqid('token_');

        $form = $this->formFactory->create(CustomerType::class);
        $form->submit($data);

        if ($form->isValid()) {
            if ($userAuthenticated) {
                if ($data['socialToken']) {
                    $userFacebook = $this->FacebookSdk
                        ->getUserFacebook($data['socialToken']);

                    $userFindApiKey = $this->registry->getRepository('AppBundle:Customer')
                        ->findOneByApiKey($apiKeyInHeader);
                    $userFindApiKey->setEmail($userFacebook->getEmail());
                    $userFindApiKey->setFacebookId($userFacebook->getId());
                    $userFindApiKey->setFirstName($userFacebook->getFirstName());
                    $userFindApiKey->setLastName($userFacebook->getLastName());
                    //$userFindApiKey->setApiKey($apiKey);
                    $this->registry->getManager()->flush();

                    return $userFindApiKey;
                }

                if ($data['firstName'] && $data['email'] && $data['lastName']) {
                    $userFindApiKey = $this->registry->getRepository('AppBundle:Customer')
                        ->findOneByApiKey($apiKeyInHeader);
                    $userFindApiKey->setFirstName($data['firstName']);
                    $userFindApiKey->setLastName($data['lastName']);
                    $userFindApiKey->setEmail($data['email']);
                    $this->registry->getManager()->flush();

                    return $userFindApiKey;
                } else {
                    return '401 Invalid email/first_name/last_name';
                }
            } elseif ($apiKeyInHeader) {
                return '403 Not valid apiKey';
            } else {
                $customer = new Customer();
                $customer->setUsername('customer');
                $customer->setApiKey($apiKey);
                $this->registry->getManager()->persist($customer);
                $this->registry->getManager()->flush();

                return $customer;
            }
        }

        return $form;
    }
}
