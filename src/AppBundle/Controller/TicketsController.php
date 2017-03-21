<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @RouteResource("Ticket")
 */
class TicketsController extends Controller
{
    /**
     * @RestView
     */
    public function freeAction($id)
    {
        # TODO
        $response = [
            'message' => 'necessary to realize controller Tickets free',
            'ticket_id' => $id
        ];

        return $response;
    }

    /**
     * @RestView
     */
    public function reserveAction($id)
    {
        # TODO
        $response = [
            'message' => 'necessary to realize controller, Tickets reserve',
            'ticket_id' => $id
        ];

        return $response;
    }
}
