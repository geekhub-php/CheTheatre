<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\RequestParam;
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
     * @Get(
     *     "/tickets/{id}",
     *     requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"}
     *     )
     * @ParamConverter("ticket", class="AppBundle:Ticket")
     * @RequestParam(name="id", requirements="[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}")
     * @RestView(serializerGroups={"get_ticket"})
     */
    public function getAction(Ticket $ticket)
    {
        return $ticket;
    }

    /**
     * @Patch(
     *     "/tickets/{id}/free",
     *     requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"}
     *     )
     * @RequestParam(name="id", requirements="[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}")
     * @ParamConverter("ticket", class="AppBundle:Ticket")
     * @RestView(statusCode=204)
     */
    public function freeAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket->setStatus(Ticket::STATUS_FREE);
        $em->flush();
    }

    /**
     * @Patch(
     *     "/tickets/{id}/reserve",
     *     requirements={"id" = "[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}"}
     *     )
     * @RequestParam(name="id", requirements="[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}")
     * @ParamConverter("ticket", class="AppBundle:Ticket")
     * @RestView(statusCode=204)
     */
    public function reserveAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket->setStatus(Ticket::STATUS_BOOKED);
        $em->flush();
    }
}
