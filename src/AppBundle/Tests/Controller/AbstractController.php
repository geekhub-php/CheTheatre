<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\FixturesLoader;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractController extends WebTestCase
{
    protected static $options = [
        'environment' => 'test',
        'debug' => true,
    ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Client
     */
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

    protected function dump($content, $destination = '/var/www/test.html')
    {
        $filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $filesystem->dumpFile($destination, $content);
    }

    /**
     * @param string $path
     * @param string $method
     * @param int $expectedStatusCode
     * @param array $headers
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function request($path, $method = 'GET', $expectedStatusCode = 200, $headers = [])
    {
        $client = $this->getClient();

        $crawler = $client->request(
            $method,
            $path,
            array(),
            array(),
            $this->prepareRequestHeaders($headers)
        );

        self::assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            sprintf(
                'We expected that uri "%s" will return %s status code, but had received %d',
                $path,
                $expectedStatusCode,
                $client->getResponse()->getStatusCode()
            )
        );

        return $crawler;
    }

    /**
     * Selects form by a button by name or alt value for images.
     *
     * @param Crawler $pageObject
     * @return \Symfony\Component\DomCrawler\Form
     */
    protected function getConfirmDeleteFormObject(Crawler $pageObject)
    {
        return $pageObject
            ->filter('body > div > aside.right-side > section.content '
                .'> div > div > div > div.box-footer.clearfix > form > button')
            ->form();
    }

    /**
     * @param array $options
     * @param array $server
     * @return Client
     */
    protected function getClient(array $options = array(), array $server = array())
    {
        if (!$this->client) {
            $this->client = static::createClient($options, $server);
        }

        return $this->client;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if (!$this->container) {
            $this->container = static::$kernel->getContainer();
        }

        return $this->container;
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    private function prepareRequestHeaders($headers = [])
    {
        $requestHeaders = [];
        foreach ($headers as $headerName => $headerValue) {
            $requestHeaders['HTTP_'.$headerName] = $headerValue;
        }

        return $requestHeaders;
    }

    protected function getHttpHost()
    {
        return $this->getContainer()->hasParameter('local_domain')
            ? $this->getContainer()->getParameter('local_domain')
            : 'localhost'
            ;
    }

    protected function logIn($username = 'admin', $password = '111111')
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form();
        $client->submit($form, ['_username' => $username, '_password' => $password]);

        return $client;
    }

    /**
     * @param array $fixtures
     * @param string|null $dir
     */
    protected function loadFixtures(array $fixtures, $dir = null)
    {
        $loader = new FixturesLoader($dir);
        $loader->setContainer($this->getContainer());

        foreach ($fixtures as $fixture) {
            $loader->addFixture($fixture);
        }

        $loader->load($this->getEm());
    }
}
