<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerfomanceEventsControllerTest extends WebTestCase
{
    public function testGetPerfomanceEvents()
    {
        $client = static::createClient();
        $client->request('GET', '/perfomance-events');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerfomanceEvents()
    {
        $client = static::createClient();
        $client->request('GET', '/perfomance-events');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerfomanceEventsId()
    {
        $client = static::createClient();
        $client->request('GET', '/perfomance-events/{id}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerfomanceEventsId()
    {
        $client = static::createClient();
        $client->request('GET', '/perfomance-events/{id}');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }
}
