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
     * @dataProvider urlProvider
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
     * @dataProvider urlProvider
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
            ['GET', '/orders']
        ];
    }
}
