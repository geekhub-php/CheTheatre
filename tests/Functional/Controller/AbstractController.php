<?php

namespace App\Tests\Functional\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractController extends WebTestCase
{
    protected static $options = [
        'environment' => 'test',
        'debug' => true,
    ];
    protected $em;
    protected $sessionClient;

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
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function request($path, $method = 'GET', $expectedStatusCode = 200, array $headers = [])
    {
        $client = $this->getSessionClient();

        $crawler = $client->request($method, $path, [], [], $headers);
        file_put_contents('/tmp/test.html', $client->getResponse()->getContent());
        $this->assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            sprintf('We expected that uri "%s" will return %s status code, but had received %d', $path, $expectedStatusCode, $client->getResponse()->getStatusCode())
        );

        return $crawler;
    }

    protected function restRequest(string $path, string $method = 'GET', int $expectedStatusCode = 200)
    {
        return $this->request($path, $method, $expectedStatusCode, ['HTTP_accept' => 'application/json']);
    }

    /**
     * Selects form by a button by name or alt value for images.
     *
     * @param Crawler $pageObject
     * @return \Symfony\Component\DomCrawler\Form
     */
    protected function getConfirmDeleteFormObject(Crawler $pageObject)
    {
        return $pageObject->filter('body > div > div > section.content > div > div > div > div.box-footer.clearfix > form > button')->form();
    }

    protected function getSessionClient(array $options = array(), array $server = array())
    {
        if (!$this->sessionClient) {
            $this->sessionClient = static::createClient($options, $server);
        }

        return $this->sessionClient;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return self::$container;
    }

    protected function getHttpHost()
    {
        return $this->getContainer()->hasParameter('local_domain')
            ? $this->getContainer()->getParameter('local_domain')
            : 'localhost'
            ;
    }

    protected function logIn($username = 'admin', $password = 'admin')
    {
        $client = $this->getSessionClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('.form-signin')->form();
        $client->submit($form, ['form_login[username]' => $username, 'form_login[password]' => $password]);

        return $client;
    }
}
