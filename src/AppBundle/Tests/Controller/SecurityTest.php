<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;

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
    }

    /**
     * @dataProvider apiSecuredAreaUrlProvider
     *
     * @param array $method
     * @param array $url
     */
    public function testNoApiKeyTokenInHeader($method, $url)
    {
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
     * @dataProvider apiSecuredAreaUrlProvider
     *
     * @param array $method
     * @param array $url
     */
    public function testInvalidApiKeyTokenInHeader($method, $url)
    {
        $client = $this->getClient();
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
     * @dataProvider apiSecuredAreaUrlProvider
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
     * @dataProvider apiNotSecuredAreaUrlProvider
     *
     * @param array $method
     * @param array $url
     */
    public function testApiNotSecuredArea($method, $url)
    {
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
            200,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    /**
     * @return array
     */
    public function apiSecuredAreaUrlProvider()
    {

        return [
            // api: /users
            ['POST', '/users/login/update'],
            ['POST', '/users/login/social'],
            ['POST', '/users/logout'],
            ['GET', '/users/me'],
            // api: /orders
            ['GET', '/orders']
            // TODO: Add api_secured_area urls
        ];
    }

    /**
     * @return array
     */
    public function apiNotSecuredAreaUrlProvider()
    {
        return [
            ['GET', '/doc/'],
            ['POST', '/users/register'],
            ['GET', '/employees'],
            ['GET', '/posts'],
            ['GET', '/performances'],
            ['GET', '/performanceevents'],
            ['GET', '/histories']
            // TODO: Add api_not_secured_area urls
        ];
    }
}
