<?php

namespace AppBundle\Controller;

use AppBundle\Model\CustomerResponse;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @RouteResource("Customer")
 */
class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @Post("/customers/login/new")
     * @return View
     */
    public function loginNewAction(Request $request)
    {
        $apiKey = $request->headers->get('API-Key-Token');
        $user = $this->getUser();

        if (!$user && !$apiKey) {
            $customer = $this->get('customer_login')
                ->newCustomer();

            $customerResponse = new CustomerResponse($customer);

            return View::create($customerResponse);
        }

        throw new HttpException(401, 'Invalid API-Key-Token');
    }

    /**
     * @param Request $request
     * @Post("/customers/login/update")
     * @return View
     */
    public function loginUpdateAction(Request $request)
    {
        $customer = $this->get('customer_login')
            ->updateCustomer(
                $request->headers->get('API-Key-Token'),
                $request->getContent()
            );

        $customerResponse = new CustomerResponse($customer);

        return View::create($customerResponse);
    }

    /**
     * @param Request $request
     * @Post("/customers/login/social")
     * @return View
     */
    public function loginSocialAction(Request $request)
    {
        $customer = $this->get('customer_login')
            ->loginSocialNetwork(
                $request->headers->get('API-Key-Token'),
                $request->getContent()
            );

        $customerResponse = new CustomerResponse($customer);

        return View::create($customerResponse);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function logoutAction(Request $request)
    {
        $apiKey = $request->headers->get('API-Key-Token');
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKey]);
        $customer->setApiKey(null);

        $em->flush();

        return View::create(null, 204);
    }
}
