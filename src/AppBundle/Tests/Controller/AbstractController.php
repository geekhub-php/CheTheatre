<?php

namespace AppBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractController extends WebTestCase
{
    protected static $options = [
        'environment' => 'test',
        'debug'       => true,
    ];
    protected $container;
    protected $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel(self::$options);
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        if (!$this->em) {
            $this->em = $this->getContainer()->get('doctrine')->getManager();
        }

        return $this->em;
    }

    /**
     * @param string $path
     * @param string $method
     * @param int $expectedStatusCode
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function request($path, $method = 'GET', $expectedStatusCode = 200)
    {
        $client = $this->getClient();

        $crawler = $client->request($method, $path);
        $this->assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            sprintf('We expected that uri "%s" will return %s status code, but had received %d', $path, $expectedStatusCode, $client->getResponse()->getStatusCode())
        );

        return $crawler;
    }

    protected function getClient(array $server = array())
    {
        $client = $this->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        if (!$this->container) {
            $this->container = static::$kernel->getContainer();
        }

        return $this->container;
    }

    protected function getHttpHost()
    {
        return $this->getContainer()->hasParameter('local_domain')
            ? $this->getContainer()->getParameter('local_domain')
            : 'localhost'
            ;
    }
}
