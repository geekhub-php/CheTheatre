<?php

namespace AppBundle\Controller;

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
     * @return View
     */
    public function loginAction(Request $request)
    {
        $validatorResult = $this->get('service_customers_login_validator')
            ->resultOptions(
                $this->getUser(),
                $request->request->all(),
                $request->headers->get('API-Key-Token')
            );

        return View::create($validatorResult);
    }
}
