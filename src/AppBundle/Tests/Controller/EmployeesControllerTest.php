<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeesControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testGetEmployees()
    {
        $client = static::createClient();
        $client->request('GET', '/employees');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployees()
    {
        $client = static::createClient();
        $client->request('GET', '/employees');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetEmployeesSlug()
    {
        $slug = $this->em->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/employees/'.$slug);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployeesSlug()
    {
        $slug = $this->em->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/employees/'.$slug);
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetEmployeesSlugRoles()
    {
        $slug = $this->em->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/employees/'.$slug.'/roles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorEmployeesSlugRoles()
    {
        $slug = $this->em->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/employees/'.$slug.'/roles');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
}
