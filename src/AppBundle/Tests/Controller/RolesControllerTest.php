<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RolesControllerTest extends WebTestCase
{

    public function testGetRoles()
    {
        $client = static::createClient();
        $client->request('GET', '/roles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorRoles()
    {
        $client = static::createClient();
        $client->request('GET', '/roles');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetRolesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/roles/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorRolesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/roles/{slug}');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }
}
