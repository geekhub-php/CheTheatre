<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Model\CustomerResponse;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Customer")
 */
class CustomerController extends Controller
{
    /**
     * @param Request $request
     *
     * @return CustomerResponse
     */
    public function newAction(Request $request)
    {
        $apiKey = $request->headers->get('API-Key-Token');
        $user = $this->getUser();

        if (!$user && !$apiKey) {
            $customer = $this->get('customer_login')
                ->newCustomer();

            return new CustomerResponse($customer);
        }

        $response = [
            '401' => 'Invalid API-Key-Token',
        ];

        return View::create($response, 401);
    }

    /**
     * @param Request $request
     *
     * @return CustomerResponse
     */
    public function updateAction(Request $request)
    {
        $customer = $this->get('customer_login')
            ->updateCustomer(
                $request->headers->get('API-Key-Token'),
                $request->getContent()
            );
        if ($customer) {
            return new CustomerResponse($customer);
        }

        $response = [
            '401' => 'invalid first_name, last_name, email',
        ];

        return View::create($response, 401);
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function logoutAction(Request $request)
    {
        $apiKeyHead = $request->headers->get('API-Key-Token');

        $response = [
            '204' => 'Successful operation',
        ];

        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKeyHead]);
        $customer->setApiKey(null);
        $em->flush();

        return View::create($response, 204);
    }
}
