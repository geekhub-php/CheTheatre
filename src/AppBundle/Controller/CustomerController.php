<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{
    public function customerLoginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $apiKeyHead = $request->headers->get('API-Key-Token');
        $facebookToken = $request->request->get('social_token');
        $apiKey = uniqid('token_');
        $firstNameHead = $request->request->get('first_name');
        $lastNameHead = $request->request->get('last_name');
        $emailHead = $request->request->get('email');

        $ValidatorResult = $this->get('service_customers_login_validator')
            ->resultOptions($this->getUser(), $apiKeyHead, $facebookToken, $firstNameHead, $lastNameHead, $emailHead);

        switch ($ValidatorResult) {
            case 'social token true':
                $UserFacebook = $this->get('service_facebook_sdk')
                    ->getUserFacebook($facebookToken);
                $userFindApiKey = $em->getRepository('AppBundle:Customer')
                    ->findUsernameByApiKey($apiKeyHead);
                $userFindApiKey->setEmail($UserFacebook->getEmail());
                $userFindApiKey->setFacebookID($UserFacebook->getId());
                $userFindApiKey->setFirstname($UserFacebook->getFirstName());
                $userFindApiKey->setLastname($UserFacebook->getLastName());
                $userFindApiKey->setApiKey($apiKey);
                $em->flush();

                return new JsonResponse('costumer');

            break;
            case 'Invalid email/first_name/last_name':
                return new JsonResponse('401');
            break;
            case 'customer input of the form':
                $userFindApiKey = $em->getRepository('AppBundle:Customer')
                    ->findUsernameByApiKey($apiKeyHead);
                $userFindApiKey->setFirstname($firstNameHead);
                $userFindApiKey->setLastname($lastNameHead);
                $userFindApiKey->setEmail($emailHead);
                $em->flush();

                return new JsonResponse('customer');
             break;
            case 'not valid apiKey':
                return new JsonResponse('403');
            break;
            case 'new costomer':
                $costumer = new Customer();
                $costumer->setUsername('customer');
                $costumer->setApiKey($apiKey);
                $em->persist($costumer);
                $em->flush();

                return new JsonResponse('customer');
             break;
        }
    }
}
