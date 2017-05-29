<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use Monolog\Logger;

class SecurityTest extends AbstractApiController
{
    public function setUp()
    {
        parent::setUp();

        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:User', 'u')
            ->where('u.apiKey = :apiKey')
            ->setParameter('apiKey', 'token_44444444')
            ->getQuery()
            ->execute();

        $user = new User();
        $user
            ->setUsername('user')
            ->setApiKey('token_44444444')
            ->setRole('ROLE_NONE');
        $this->getEm()->persist($user);
        $this->getEm()->flush();

//        $this
//            ->getEm()
//            ->createQueryBuilder()
//            ->delete('AppBundle:Client', 'c')
//            ->where('c.ip = :ip')
//            ->setParameter('ip', '11.11.11.11')
//            ->getQuery()
//            ->execute();
    }
    public function deleteClients()
    {
        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:Client', 'c')
            ->getQuery()
            ->execute();
    }

    public function prototypeCountAttemptsInvalidApiKeyToken($countAttempts, $countAssertSame)
    {
        $this->deleteClients();
        $clientDb = new Client();
        $clientDb
            ->setIp('33.33.33.33')
            ->setCountAttempts($countAttempts)
            ->setBanned(false);
        $this->getEm()->persist($clientDb);
        $this->getEm()->flush();

        $client = static::createClient([], ['REMOTE_ADDR' => '33.33.33.33']);
        $logger = $this->createMock(Logger::class);
        $logger->expects($spy = $this->any())
            ->method('err');
        $client->getContainer()->set('monolog.logger.security_error', $logger);
        $client->request(
            'POST', '/users/login/update',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111_invalid',
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $invocations = $spy->getInvocations();
        self::assertSame($countAssertSame, count($invocations));
    }

    public function testCountAttemptsInvalidApiKeyToken()
    {
        $this->prototypeCountAttemptsInvalidApiKeyToken(0, 1);
        $this->prototypeCountAttemptsInvalidApiKeyToken(49, 1);
        $this->prototypeCountAttemptsInvalidApiKeyToken(99, 1);
        $this->prototypeCountAttemptsInvalidApiKeyToken(2, 0);
        $this->prototypeCountAttemptsInvalidApiKeyToken(30, 0);
    }

    /**
     * @dataProvider urlProvider
     *
     * @param array $method
     * @param array $url
     */
    public function testNoApiKeyTokenInHeader($method, $url)
    {
        $this->deleteClients();
        $client = $this->getClient();
        $client->request(
            $method,
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        self::assertEquals(
            401,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider urlProvider
     *
     * @param array $method
     * @param array $url
     */
    public function testInvalidApiKeyTokenInHeader($method, $url)
    {
        $client = static::createClient([], ['REMOTE_ADDR' => '11.11.11.11']);
        $logger = $this->createMock(Logger::class);
        $client->getContainer()->set('monolog.logger.security_error', $logger);
        $client->request(
            $method,
            $url,
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111_invalid',
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        self::assertEquals(
            403,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider urlProvider
     *
     * @param array $method
     * @param array $url
     */
    public function testInvalidUserRole($method, $url)
    {
        $client = $this->getClient();
        $client->request(
            $method,
            $url,
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_44444444',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        self::assertEquals(
            403,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    /**
     * @return array
     */
    public function urlProvider()
    {
        return [
            // api: /users
            ['POST', '/users/login/update'],
            ['POST', '/users/login/social'],
            ['POST', '/users/logout'],
            ['GET', '/users/me'],
            // api: /orders
            ['GET', '/orders'],
        ];
    }
}
