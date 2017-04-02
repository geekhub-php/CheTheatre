<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @RouteResource("Ticket")
 */
class TicketsController extends Controller
{
    /**
     * @Get(requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"})
     * @ParamConverter("id", class="AppBundle:Ticket")
     * @RestView(serializerGroups={"get_ticket"})
     */
    public function getAction(Ticket $id)
    {
        return $id;
    }

    /**
     * @RestView(statusCode=204)
     * @Patch(requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"})
     * @ParamConverter("id", class="AppBundle:Ticket")
     */
    public function freeAction(Ticket $id)
    {
        $em = $this->getDoctrine()->getManager();
        $id->setStatus(Ticket::STATUS_FREE);
        $em->flush();
    }

    /**
     * @RestView(statusCode=204)
     * @Patch(requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"})
     * @ParamConverter("id", class="AppBundle:Ticket")
     */
    public function reserveAction(Ticket $id)
    {
        $em = $this->getDoctrine()->getManager();
        $id->setStatus(Ticket::STATUS_BOOKED);
        $em->flush();
    }
}
