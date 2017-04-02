<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Ticket;

class TicketsControllerTest extends AbstractApiController
{

    public function testGetTicketsId()
    {
        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
        $this->request('/tickets/'.$id);
        $this->request('/tickets/550e8400-e29b-41d4-a716-446655440000', 'GET', 404);
    }

    public function testPatchTicketsId()
    {
        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
//        $this->request('/tickets/'.$id.'/free', 'PATCH', 204);
    }
}
