<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Model\FacebookResponse;
use AppBundle\Services\FacebookUserProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomerControllerTest extends AbstractController
{
    public function setUp()
    {
        parent::setUp();

        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:Customer', 'c')
            ->where('c.apiKey = :apiKey')
            ->setParameter('apiKey', 'token_11111111')
            ->getQuery()
            ->execute();
        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:Customer', 'c')
            ->where('c.apiKey = :apiKey')
            ->setParameter('apiKey', 'token_22222222')
            ->getQuery()
            ->execute();
        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:Customer', 'c')
            ->where('c.apiKey = :apiKey')
            ->setParameter('apiKey', 'token_33333333')
            ->getQuery()
            ->execute();

        $customer1 = new Customer();
        $customer1
            ->setUsername('customer')
            ->setApiKey('token_11111111');
        $customer2 = new Customer();
        $customer2
            ->setUsername('customer')
            ->setApiKey('token_22222222')
            ->setFacebookId('fb_id_22222222');
        $customer3 = new Customer();
        $customer3
            ->setUsername('customer')
            ->setApiKey('token_33333333');

        $this->getEm()->persist($customer1);
        $this->getEm()->persist($customer2);
        $this->getEm()->persist($customer3);
        $this->getEm()->flush();
    }

    public function testSuccessLoginNew()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login/new',
            [],
            [],
            [
                'HTTP_API-Key-Token' => '',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "",
                "first_name": "",
                "last_name": ""
            }'
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertNotNull($content['api_key']);
    }

    public function testSuccessLoginUpdate()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login/update',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "john.doe@example.com",
                "first_name": "John",
                "last_name": "Doe",
                "social_network": "",
                "social_token": ""
            }'
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals('John', $content['customer']['first_name']);
        self::assertEquals('Doe', $content['customer']['last_name']);
        self::assertEquals('john.doe@example.com', $content['customer']['email']);
        self::assertEquals('token_11111111', $content['api_key']);
    }

    public function testSuccessLoginFacebook()
    {
        $userFacebook = new FacebookResponse();
        $userFacebook->setId('fb_id_11111111');
        $userFacebook->setEmail('john.doe@example.com');
        $userFacebook->setFirstName('John');
        $userFacebook->setLastName('Doe');

        $client = $this->getClient();

        $facebook = $this->createMock(FacebookUserProvider::class);

        $facebook->expects($this->once())
            ->method('getUser')
            ->with('social_token_11111111')
            ->will($this->returnValue($userFacebook));

        $client->getContainer()->set('facebook_user_provider', $facebook);

        $client->request(
            'POST',
            '/customers/login/social',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "",
                "first_name": "",
                "last_name": "",
                "social_network": "facebook",
                "social_token": "social_token_11111111"
            }'
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals($userFacebook->getFirstName(), $content['customer']['first_name']);
        self::assertEquals($userFacebook->getLastName(), $content['customer']['last_name']);
        self::assertEquals($userFacebook->getEmail(), $content['customer']['email']);
        self::assertEquals('token_11111111', $content['api_key']);
    }

    public function testSuccessLoginFacebookExistingUser()
    {
        $userFacebook = new FacebookResponse();
        $userFacebook->setId('fb_id_22222222');

        $client = $this->getClient();

        $facebook = $this->createMock(FacebookUserProvider::class);

        $facebook->expects($this->once())
            ->method('getUser')
            ->with('social_token_22222222')
            ->will($this->returnValue($userFacebook));

        $client->getContainer()->set('facebook_user_provider', $facebook);

        $client->request(
            'POST',
            '/customers/login/social',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_33333333',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "",
                "first_name": "",
                "last_name": "",
                "social_network": "facebook",
                "social_token": "social_token_22222222"
            }'
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals('token_33333333', $content['api_key']);
    }

    public function testFailLoginNew()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login/new',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111_invalid',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "",
                "first_name": "",
                "last_name": "",
                "social_network": "",
                "social_token": ""
            }'
        );

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testFailLoginUpdate()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login/update',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "john.doe111example.com",
                "first_name": "John111",
                "last_name": "Doe111",
                "social_network": "",
                "social_token": ""
            }'
        );

        self::assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testFailLoginFacebook()
    {
        $client = $this->getClient();

        $facebook = $this->createMock(FacebookUserProvider::class);

        $facebook->expects($this->once())
            ->method('getUser')
            ->with('social_token_11111111_invalid')
            ->will($this->throwException(new HttpException(400, 'Social login error')));

        $client->getContainer()->set('facebook_user_provider', $facebook);

        $client->request(
            'POST',
            '/customers/login/social',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111',
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
                "email": "",
                "first_name": "",
                "last_name": "",
                "social_network": "facebook",
                "social_token": "social_token_11111111_invalid"
            }'
        );

        self::assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testFailLogoutApiKeyToken()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/logout',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111_invalid',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testFailLogoutWithoutApiKeyToken()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/logout',
            [],
            [],
            []
        );

        self::assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testSuccessLogoutApiKeyToken()
    {
        $client = $this->getClient();

        $customer = $this->getEm()
            ->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => 'token_11111111']);
        self::assertNotNull($customer->getApiKey());

        $client->request(
            'POST',
            '/customers/logout',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111',
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $customer = $this->getEm()
            ->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey' => 'token_11111111']);
        self::assertEquals(null, $customer);
        self::assertEquals(204, $client->getResponse()->getStatusCode());
        $client->request(
            'POST',
            '/customers/logout',
            [],
            [],
            [
                'HTTP_API-Key-Token' => 'token_11111111',
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }
}
