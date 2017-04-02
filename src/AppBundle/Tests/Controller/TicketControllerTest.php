<?php

namespace AppBundle\Tests\Controller;

class TicketControllerTest extends AbstractApiController
{
    public function testGetTicketsId()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Ticket')->findOneBy([])->getId();
        $fakeSlug = 'a88e899f-1774-11e7-b3bf-9457a501d174';

        $this->request('/tickets/'.$slug, 'GET', 200);
        $this->request('/tickets/'.$fakeSlug, 'GET', 404);
    }

    public function testFreeTicketsId()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Ticket')->findOneBy([])->getId();
        $this->request('tickets/'.$slug.'/free', 'PATCH', 204);
    }

    public function testReserveTickets()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Ticket')->findOneBy(array('status' => 'free'))->getId();
        $this->request('/tickets/'.$slug.'/free', 'PATCH', 204);
        $slug = $this->getEm()->getRepository('AppBundle:Ticket')->findOneBy(array('status' => 'booked'))->getId();
        $this->request('/tickets/'.$slug.'/free', 'PATCH', 409);
    }
}
