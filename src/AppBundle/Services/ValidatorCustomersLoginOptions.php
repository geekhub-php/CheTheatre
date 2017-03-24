<?php

namespace AppBundle\Services;

use AppBundle\Form\Customer\CustomerType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\DTOCustomer;
use AppBundle\Entity\Customer;


class ValidatorCustomersLoginOptions
{
    private $registry;
    public $facebookSdk;
    public function __construct(ManagerRegistry $registry, FormFactoryInterface $formFactory, FacebookSdk $facebookSdk)
    {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->FacebookSdk=$facebookSdk;
    }


    public function resultOptions($userAuthenticated, $data, $apiKeyHead)
    {
        $apiKey = uniqid('token_');

        if ($userAuthenticated) {


            $DTOCustomer = new DTOCustomer();
            $form=$this->formFactory->create(new CustomerType(), $DTOCustomer);
            $form["firstName"]->setData($data['first_name']);
            $form["lastName"]->setData($data['last_name']);
            $form["socialToken"]->setData($data['social_token']);
            $form["socialNetwork"]->setData($data['social_network']);
            $form["email"]->setData($data['email']);

            $form->submit($data);

           if ($data['social_token']) {

               $UserFacebook=$this->FacebookSdk
                   ->getUserFacebook($data['social_token']);

               $userFindApiKey = $this->registry->getRepository('AppBundle:Customer')
                  ->findUsernameByApiKey($apiKeyHead);
               $userFindApiKey->setEmail($UserFacebook->getEmail());
               $userFindApiKey->setFacebookID($UserFacebook->getId());
               $userFindApiKey->setFirstname($UserFacebook->getFirstName());
               $userFindApiKey->setLastname($UserFacebook->getLastName());
               $userFindApiKey->setApiKey($apiKey);
               $this->registry->getManager()->flush();

               return $UserFacebook;

           }

            if ($data['first_name'] && $data['email'] && $data['last_name'] && $form["email"]->isValid()) {

                $userFindApiKey = $this->registry->getRepository('AppBundle:Customer')
                    ->findUsernameByApiKey($apiKeyHead);
                $userFindApiKey->setFirstname($data['first_name']);
                $userFindApiKey->setLastname($data['last_name']);
                $userFindApiKey->setEmail($data['email']);
                $this->registry->getManager()->flush();

                return 'customer input of the form';
            } else {
                return 'Invalid email/first_name/last_name';
            }
        } elseif ($apiKeyHead) {
            return 'not valid apiKey';
        } else {
            $costumer = new Customer();
            $costumer->setUsername('customer');
            $costumer->setApiKey($apiKey);
            $this->registry->getManager()->persist($costumer);
            $this->registry->getManager()->flush();
            return 'new costomer';
        }

    }
}
