<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{
    public function customerLoginAction(Request $request)
    {
        $apiKeyHead = $request->headers->get('API-Key-Token');
        $data = $request->request->all();

        $ValidatorResult = $this->get('service_customers_login_validator')
            ->resultOptions($this->getUser(), $data, $apiKeyHead);

        return new JsonResponse($ValidatorResult);
    }
}
