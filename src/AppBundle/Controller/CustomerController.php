<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\CustomerType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Customer")
 */
class CustomerController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse|View
     */
    public function loginAction(Request $request)
    {
        $form = $this->createForm(CustomerType::class);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $apiKeyFromRequest = $request->headers->get('API-Key-Token');
            $socialNetwork = $request->request->get('socialNetwork');
            $socialToken = $request->request->get('socialToken');
            $apiKey = uniqid('token_');
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
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

                    return new JsonResponse('customer social');
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

                    return new JsonResponse('customer form');
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

        return View::create($form, 400);
    }
}
