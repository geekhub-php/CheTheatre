<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformancesControllerTest extends WebTestCase
{

    public function testGetPerformances()
    {
        $client = static::createClient();
        $client->request('GET', '/performances');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformances()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlugRoles()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/roles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlugRoles()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/roles/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlugRolesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/roles/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlugRolesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/roles/error/{slug}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlugPerfomanceEvents()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/perfomance-events');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlugPerfomanceEvents()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/perfomance-events/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlugPerfomanceEventsSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/perfomance-events/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlugPerfomanceEventsSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/performances/{slug}/perfomance-events/error/{slug}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
