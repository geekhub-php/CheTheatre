<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
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
        //This done not in right way (Ticket $ticket) to have RESTfully looking route: /tickets/{id}
        $ticket = $id;

        return $ticket;
    }

    /**
     * @RestView(statusCode=204)
     * @Patch(requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"})
     * @ParamConverter("id", class="AppBundle:Ticket")
     */
    public function freeAction(Ticket $id)
    {
        //This done not in right way (Ticket $ticket) to have RESTfully looking route: /tickets/{id}
        $ticket = $id;

        $this->denyAccessUnlessGranted('edit', $ticket);
        $em = $this->getDoctrine()->getManager();
        $this->get('app.order.manager')->removeOrderFromTicket($ticket);
        $em->flush();
    }

    /**
     * @RestView(statusCode=204)
     * @Patch(requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"})
     * @ParamConverter("id", class="AppBundle:Ticket")
     */
    public function reserveAction(Ticket $id)
    {
        //This done not in right way (Ticket $ticket) to have RESTfully looking route: /tickets/{id}
        $ticket = $id;

        $em = $this->getDoctrine()->getManager();
        $this->get('app.order.manager')->addOrderToTicket($ticket);
        $em->flush();
    }
}
