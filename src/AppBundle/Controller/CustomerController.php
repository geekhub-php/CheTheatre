<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @RouteResource("Customer")
 */
class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function loginAction(Request $request)
    {
        $validatorResult = $this->get('customer_login_validator')
            ->resultOptions(
                $this->getUser(),
                $request->request->all(),
                $request->headers->get('API-Key-Token')
            );

        return View::create($validatorResult);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function logoutAction(Request $request)
    {
        $apiKeyHead=$request->headers->get('API-Key-Token');

        $response = [
            '204' => 'Successful operation'
        ];

        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => $apiKeyHead]);
        $customer->setApiKey(null);
        $em->flush();

        return View::create($response, 204);
    }

}
