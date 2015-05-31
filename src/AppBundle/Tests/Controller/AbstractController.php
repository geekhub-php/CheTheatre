<?php

namespace AppBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractController extends WebTestCase
{
    protected static $options = [
        'environment' => 'test',
        'debug'       => true,
    ];
    protected $container;
    protected $em;
    protected $client;

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
        if (!$this->client) {
            $this->client = $this->getContainer()->get('test.client');
            $this->client->setServerParameters($server);
        }

        return $this->client;
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

    protected function logIn()
    {
        $session = $this->getContainer()->get('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_SUPER_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $this->getContainer()->get('security.token_storage')->setToken($token);

        $cookie = new Cookie($session->getName(), $session->getId());
        $client = $this->getClient();

        $client->getCookieJar()->set($cookie);

        return $client;
    }
}
