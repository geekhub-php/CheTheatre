<?php

namespace AppBundle\Controller;

use AppBundle\Model\CustomerResponse;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * @RouteResource("Customer")
 */
class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @Post("/customers/login/social")
     * @return View
     */
    public function loginSocialAction(Request $request)
    {
        $customer = $this->get('jms_serializer')->deserialize(
            $request->getContent(),
            CustomerResponse::class,
            'json'
        );

        $errors = $this->get('validator')->validate(
            $customer,
            null,
            'socialNetwork'
        );

        if (count($errors) > 0) {
            throw new HttpException(400, 'Validation error');
        }

        $customerResponse = new CustomerResponse(
            $this->get('customer_login')->loginSocialNetwork(
                $customer->getSocialNetwork(),
                $customer->getSocialToken(),
                $request->headers->get('API-Key-Token')
            )
        );

        return View::create($customerResponse);
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
