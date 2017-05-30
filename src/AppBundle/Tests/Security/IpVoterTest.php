<?php

namespace AppBundle\Tests\Security;

use AppBundle\Entity\Client;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IpVoterTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected static $options = [
        'environment' => 'test',
        'debug' => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        self::bootKernel(self::$options);
    }

    public function tearDown()
    {
        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:Client', 'c')
            ->getQuery()
            ->execute();
    }

    /**
     * @dataProvider voteProvider
     *
     * @param $expectedStatusCode
     * @param $ip
     * @param null $clientData
     */
    public function testVote($expectedStatusCode, $ip, $clientData = null)
    {
        $this->tearDown();
        if ($clientData) {
            $client = new Client();
            $client
                ->setIp($ip)
                ->setBanned($clientData['isBanned'])
                ->setCountAttempts($clientData['countAttempts']);
            $this->getEm()->persist($client);
            $this->getEm()->flush();
        }

        $httpClient = static::createClient([], ['REMOTE_ADDR' => $ip]);
        $httpClient->request('GET', '/doc/');

        self::assertEquals($expectedStatusCode, $httpClient->getResponse()->getStatusCode());
    }

    public function voteProvider()
    {
        return [
            'Forbidden' => [
                'statusCode' => 403,
                'ip' => '192.168.5.895',
                [
                    'isBanned' => true,
                    'countAttempts' => 1,
                ],
            ],
            'Client exist, not banned' => [
                'statusCode' => 200,
                'ip' => '192.168.5.895',
                [
                    'isBanned' => false,
                    'countAttempts' => 100,
                ],
            ],
            'Client not exist' => [
                'statusCode' => 200,
                'ip' => '192.168.5.895',
            ],
        ];
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        if (!$this->em) {
            $this->em = $this->getContainer()->get('doctrine')->getManager();
        }

        return $this->em;
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        if (!$this->container) {
            $this->container = static::$kernel->getContainer();
        }

        return $this->container;
    }
}
