<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;

class TicketsControllerTest extends AbstractApiController
{
    const FAKE_TICKET_ID = '550e8400-e29b-41d4-a716-446655440000';

    public function setUp()
    {
        parent::setUp();

        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:User', 'u')
            ->where('u.apiKey = :apiKey')
            ->setParameter('apiKey', 'token_11111111')
            ->getQuery()
            ->execute();

        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:User', 'u')
            ->where('u.apiKey = :apiKey')
            ->setParameter('apiKey', 'token_22222222')
            ->getQuery()
            ->execute();

        $user1 = new User();
        $user1
            ->setUsername('user')
            ->setApiKey('token_11111111')
            ->setRole('ROLE_API');

        $user2 = new User();
        $user2
            ->setUsername('user')
            ->setApiKey('token_22222222')
            ->setRole('ROLE_API');

        $this->getEm()->persist($user1);
        $this->getEm()->persist($user2);
        $this->getEm()->flush();
    }

    public function testGetTicketsId()
    {
        $id = strval($this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId());
        $this->request('/tickets/'.$id);
        $this->request('/tickets/'.self::FAKE_TICKET_ID, 'GET', 404);
    }

    public function testPatchTicketsId()
    {
        $id = strval($this->getEm()->getRepository(Ticket::class)->findOneBy([])->getId());
        $headers = [
            'API-Key-Token' => 'token_11111111'
        ];
        $wrongHeaders = [
            'API-Key-Token' => 'token_22222222'
        ];
        $this->request('/tickets/'.$id.'/free', 'PATCH', 204, $headers);
        $this->request('/tickets/'.self::FAKE_TICKET_ID.'/free', 'PATCH', 404, $headers);
        $this->request('/tickets/'.$id.'/reserve', 'PATCH', 204, $headers);
        $user = $this->getEm()->getRepository(User::class)->findOneBy(['apiKey' => 'token_11111111']);
        $order = $this->getEm()->getRepository(UserOrder::class)->findLastOpenOrder($user);
        $ticket = $this->getEm()->getRepository(Ticket::class)->findOneBy([]);
        $this->assertEquals($ticket->getStatus(), Ticket::STATUS_BOOKED);
        $this->assertEquals($ticket->getUserOrder(), $order);
        $this->request('/tickets/'.$id.'/reserve', 'PATCH', 409, $headers);
        $this->request('/tickets/'.self::FAKE_TICKET_ID.'/reserve', 'PATCH', 404, $headers);
        $this->request('/tickets/'.$id.'/free', 'PATCH', 403, $wrongHeaders);
        $this->request('/tickets/'.$id.'/free', 'PATCH', 204, $headers);
        $this->getEm()->refresh($ticket);
        $this->assertEquals($ticket->getStatus(), Ticket::STATUS_FREE);
        $this->assertEquals($ticket->getUserOrder(), null);
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
