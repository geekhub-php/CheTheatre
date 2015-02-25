<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformancesControllerTest extends WebTestCase
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

    public function testGetPerformances()
    {
        $client = static::createClient();
        $client->request('GET', '/performances');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformances()
    {
        $client = static::createClient();
        $client->request('GET', '/performances');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlug()
    {
        $slug = $this->em->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/performances/'.$slug);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlug()
    {
        $slug = $this->em->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/performances/'.$slug);
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlugRoles()
    {
        $slug = $this->em->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/performances/'.$slug.'/roles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlugRoles()
    {
        $slug = $this->em->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/performances/'.$slug.'/roles');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPerformancesSlugPerformanceEvents()
    {
        $slug = $this->em->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/performances/'.$slug.'/performanceevents');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorPerformancesSlugPerformanceEvents()
    {
        $slug = $this->em->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $client = static::createClient();
        $client->request('GET', '/performances/'.$slug.'/performanceevents');
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
