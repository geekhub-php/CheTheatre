<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\TicketStatusConflictException;
use Faker\Provider\DateTime;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @RouteResource("Ticket")
 */
class TicketsController extends Controller
{

    /**
     * @ParamConverter("ticket", class="AppBundle:Ticket")
     * @RestView(serializerGroups={"get_ticket"})
     */
    public function getAction(Ticket $ticket)
    {
        return $ticket;
    }

    /**
     * @RestView(statusCode=204)
     * @ParamConverter("ticket", class="AppBundle:Ticket")
     */
    public function freeAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();

        if ($ticket->getStatus() === Ticket::STATUS_FREE) {
            throw new TicketStatusConflictException('Ticket is already free');
        }

        $ticket->setStatus(Ticket::STATUS_FREE);
        $em->flush();
    }

    /**
     * @RestView(statusCode=204)
     * @ParamConverter("ticket", class="AppBundle:Ticket")
     */
    public function reserveAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();

        if ($ticket->getStatus() === Ticket::STATUS_BOOKED) {
            throw new TicketStatusConflictException('Ticket is already booked');
        }

        $ticket->setStatus(Ticket::STATUS_BOOKED);
        $em->flush();
    }
}
