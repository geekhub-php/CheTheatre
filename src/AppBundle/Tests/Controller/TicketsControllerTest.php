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
//        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
//        $this->request('/tickets/'.$id.'/free', 'PATCH', 204);
    }

    public function testTicketsResponseFields()
    {
        $client = $this->getClient();
        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
        $client->request('GET', '/tickets/'.$id);
        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('performance_event_id', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('price_category_id', $content);
        self::assertArrayHasKey('set_date', $content);
        self::assertArrayHasKey('set_number', $content);
        self::assertArrayHasKey('status', $content);
        self::assertArrayHasKey('price', $content);
        self::assertArrayHasKey('seat', $content);
        self::assertArrayHasKey('price', $content);
        self::assertArrayHasKey('venue_sector_id', $content['seat']);
        self::assertArrayHasKey('row', $content['seat']);
        self::assertArrayHasKey('place', $content['seat']);
    }
}
