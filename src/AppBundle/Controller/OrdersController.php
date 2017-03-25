<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @RouteResource("Order")
 */
class OrdersController extends Controller
{
    /**
     * @RestView
     */
    public function cgetAction()
    {
        # TODO
        $response = [
            'message' => 'necessary to realize controller to return collection of orders',
        ];

        return $response;
    }

    /**
     * @RestView
     */
    public function getAction($orderId)
    {
        # TODO
        $response = [
            'message' => 'necessary to realize controller',
            'order_id' => $orderId
        ];

        return $response;
    }
}
