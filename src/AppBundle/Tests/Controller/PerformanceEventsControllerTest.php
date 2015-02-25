<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformanceEventsControllerTest extends WebTestCase
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

    public function testGetPerformanceEvents()
    {
        $client = static::createClient();
        $client->request('GET', '/performanceevents');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformanceEvents()
    {
        $client = static::createClient();
        $client->request('GET', '/performanceevents');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformanceEventsId()
    {
        $id = $this->em->getRepository('AppBundle:PerformanceEvent')->findOneBy([])->getId();
        $client = static::createClient();
        $client->request('GET', '/performanceevents/'.$id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformanceEventsId()
    {
        $id = $this->em->getRepository('AppBundle:PerformanceEvent')->findOneBy([])->getId();
        $client = static::createClient();
        $client->request('GET', '/performanceevents/'.$id);
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
