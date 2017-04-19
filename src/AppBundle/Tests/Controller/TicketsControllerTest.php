<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;

class TicketsControllerTest extends AbstractApiController
{
    const FAKE_TICKET_ID = '550e8400-e29b-41d4-a716-446655440000';

    /** @var integer */
    private $ticketId;

    /** @var User */
    private $currentUser;

    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(
            [
                'Performance',
                'PerformanceEvent',
                'PriceCategory',
                'Ticket',
                'User'
            ],
            'Entity'
        );

        $this->ticketId     = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId();
        $this->currentUser  = $this->getEm()->getRepository(User::class)->findOneBy([]);
    }

    public function testGetTicketsId()
    {
        $this->request('/tickets/'.$this->ticketId);
        $this->request('/tickets/'.self::FAKE_TICKET_ID, 'GET', 404);
    }

    public function testPatchTicketsId()
    {
        $headers = [
            'API-Key-Token' => $this->currentUser->getApiKey(),
        ];
        $this->request('/tickets/'.$this->ticketId.'/free', 'PATCH', 204, $headers);
        $this->request('/tickets/'.self::FAKE_TICKET_ID.'/free', 'PATCH', 404, $headers);
        $this->request('/tickets/'.$this->ticketId.'/reserve', 'PATCH', 204, $headers);
        $this->request('/tickets/'.self::FAKE_TICKET_ID.'/reserve', 'PATCH', 404, $headers);
        $this->request('/tickets/'.$this->ticketId.'/free', 'PATCH', 204, $headers);
    }

    public function testTicketsResponseFields()
    {
        $client = $this->getClient();
        $client->request('GET', '/tickets/'.$this->ticketId);
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
