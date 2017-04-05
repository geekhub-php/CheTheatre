<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Ticket;

class TicketsControllerTest extends AbstractApiController
{
    const FAKE_TICKET_ID = '550e8400-e29b-41d4-a716-446655440000';

    public function testGetTicketsId()
    {
        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
        $this->request('/tickets/'.$id);
        $this->request('/tickets/'.self::FAKE_TICKET_ID, 'GET', 404);
    }

    public function testPatchTicketsId()
    {
        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
        $headers = [
            'API-Key-Token' => '802057ff9b5b4eb7fbb8856b6eb2cc5b'
        ];
        $this->request('/tickets/'.$id.'/free', 'PATCH', 204, $headers);
        $this->request('/tickets/'.self::FAKE_TICKET_ID.'/free', 'PATCH', 404, $headers);
        $this->request('/tickets/'.$id.'/reserve', 'PATCH', 204, $headers);
        $this->request('/tickets/'.self::FAKE_TICKET_ID.'/reserve', 'PATCH', 404, $headers);
        $this->request('/tickets/'.$id.'/free', 'PATCH', 204, $headers);
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
        self::assertArrayHasKey('series_date', $content);
        self::assertArrayHasKey('series_number', $content);
        self::assertArrayHasKey('status', $content);
        self::assertArrayHasKey('price', $content);
        self::assertArrayHasKey('seat', $content);
        self::assertArrayHasKey('price', $content);
        self::assertArrayHasKey('venue_sector_id', $content['seat']);
        self::assertArrayHasKey('row', $content['seat']);
        self::assertArrayHasKey('place', $content['seat']);
    }
}
