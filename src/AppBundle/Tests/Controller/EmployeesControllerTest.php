<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeesControllerTest extends WebTestCase
{

    public function testGetEmployees()
    {
        $client = static::createClient();
        $client->request('GET', '/employees');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployees()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetEmployeesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployeesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/{slug}/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetEmployeesSlugRoles()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/{slug}/roles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployeesSlugRoles()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/{slug}/roles/error');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetEmployeesSlugRolesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/{slug}/roles/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployeesSlugRolesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/employees/{slug}/roles/error/{slug}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}
