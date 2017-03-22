<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $apiKeyFromRequest = $request->headers->get('API-Key-Token');
        $socialNetwork = $request->request->get('social_network');
        $socialToken = $request->request->get('social_token');
        $apiKey = uniqid('token_');
        $firstName = $request->request->get('first_name');
        $lastName = $request->request->get('last_name');
        $email = $request->request->get('email');

        $validatorResult = $this->get('service_customers_login_validator')
            ->resultOptions($this->getUser(), $apiKeyFromRequest, $socialNetwork, $firstName, $lastName, $email);

        switch ($validatorResult) {
            case 'social network true':
                $UserFacebook = $this->get('service_facebook_sdk')
                    ->getUserFacebook($socialToken);
                $userFindApiKey = $em->getRepository('AppBundle:Customer')
                    ->findOneByApiKey($apiKeyFromRequest);
                $userFindApiKey->setEmail($UserFacebook->getEmail());
                $userFindApiKey->setFacebookId($UserFacebook->getId());
                $userFindApiKey->setFirstName($UserFacebook->getFirstName());
                $userFindApiKey->setLastName($UserFacebook->getLastName());
                $userFindApiKey->setApiKey($apiKey);
                $em->flush();

                return new JsonResponse('customer');
                break;
            case 'Invalid email/first_name/last_name':
                return new JsonResponse('401');
                break;
            case 'customer input of the form':
                $userFindApiKey = $em->getRepository('AppBundle:Customer')
                    ->findOneByApiKey($apiKeyFromRequest);
                $userFindApiKey->setFirstName($firstName);
                $userFindApiKey->setLastName($lastName);
                $userFindApiKey->setEmail($email);
                $em->flush();

                return new JsonResponse('customer');
                break;
            case 'not valid apiKey':
                return new JsonResponse('403');
                break;
            case 'new customer':
                $customer = new Customer();
                $customer->setUsername('customer');
                $customer->setApiKey($apiKey);
                $em->persist($customer);
                $em->flush();

                return new JsonResponse('customer');
                break;
            default:
                return new JsonResponse('default');
        }
    }
}
