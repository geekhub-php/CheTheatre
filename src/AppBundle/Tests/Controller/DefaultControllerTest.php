<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testGet()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetError()
    {
        $client = static::createClient();
        $client->request('GET', '/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}
