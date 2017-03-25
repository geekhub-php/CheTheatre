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

    public function resultOptions($userAuthenticated, $data, $apiKeyTokenInHeader)
    {
        $apiKeyToken = uniqid('token_');

        $form = $this->formFactory->create(CustomerType::class);
        $form->submit($data);

        if ($form->isValid()) {
            if ($userAuthenticated) {
                if ($data['socialNetwork'] == 'facebook' && $data['socialToken']) {
                    $userFacebook = $this->FacebookSdk
                        ->getUserFacebook($data['socialToken']);

                    if ($userFacebook->getId()) {
                        $userFindApiKey = $this->registry->getRepository('AppBundle:Customer')
                            ->findOneByApiKeyToken($apiKeyTokenInHeader);
                        $userFindApiKey->setEmail($userFacebook->getEmail());
                        $userFindApiKey->setFacebookId($userFacebook->getId());
                        $userFindApiKey->setFirstName($userFacebook->getFirstName());
                        $userFindApiKey->setLastName($userFacebook->getLastName());
                        //$userFindApiKey->setApiKey($apiKeyToken);
                        $this->registry->getManager()->flush();

                        return $userFindApiKey;
                    }

                    return '401 Invalid facebook';
                }

                if ($data['email'] && $data['firstName'] && $data['lastName']) {
                    $userFindApiKey = $this->registry->getRepository('AppBundle:Customer')
                        ->findOneByApiKeyToken($apiKeyTokenInHeader);
                    $userFindApiKey->setFirstName($data['firstName']);
                    $userFindApiKey->setLastName($data['lastName']);
                    $userFindApiKey->setEmail($data['email']);
                    $this->registry->getManager()->flush();

                    return $userFindApiKey;
                } else {
                    return '401 Invalid email/first_name/last_name';
                }
            } elseif ($apiKeyTokenInHeader) {
                return '403 Not valid apiKey';
            } else {
                $customer = new Customer();
                $customer->setUsername('customer');
                $customer->setApiKeyToken($apiKeyToken);
                $this->registry->getManager()->persist($customer);
                $this->registry->getManager()->flush();

                return $customer;
            }
        }

        return $form;
    }
}
