<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Customer;
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

    public function testSuccessLogin()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => '',
                'lastName' => '',
                'email' => '',
                'socialNetwork' => '',
                'socialToken' => '',
            ],
            [],
            ['HTTP_API-Key-Token' => '']
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertNotNull($content['api_key']);
    }

    public function testSuccessLoginForm()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe@example.com',
                'socialNetwork' => '',
                'socialToken' => '',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111']
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
        $userFacebook = new \stdClass();
        $userFacebook->id = 'fb_id_11111111';
        $userFacebook->email = 'john.doe@example.com';
        $userFacebook->first_name = 'John';
        $userFacebook->last_name = 'Doe';

        $client = $this->getClient();

        $facebook = $this->createMock(FacebookUserProvider::class);

        $facebook->expects($this->once())
            ->method('getUser')
            ->with('social_token_11111111')
            ->will($this->returnValue($userFacebook));

        $client->getContainer()->set('facebook_user_provider', $facebook);

        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => '',
                'lastName' => '',
                'email' => '',
                'socialNetwork' => 'facebook',
                'socialToken' => 'social_token_11111111',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111']
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals($userFacebook->first_name, $content['customer']['first_name']);
        self::assertEquals($userFacebook->last_name, $content['customer']['last_name']);
        self::assertEquals($userFacebook->email, $content['customer']['email']);
        self::assertEquals('token_11111111', $content['api_key']);
    }

    public function testSuccessLoginFacebookExistingUser()
    {
        $userFacebook = new \stdClass();
        $userFacebook->id = 'fb_id_22222222';

        $client = $this->getClient();

        $facebook = $this->createMock(FacebookUserProvider::class);

        $facebook->expects($this->once())
            ->method('getUser')
            ->with('social_token_22222222')
            ->will($this->returnValue($userFacebook));

        $client->getContainer()->set('facebook_user_provider', $facebook);

        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => '',
                'lastName' => '',
                'email' => '',
                'socialNetwork' => 'facebook',
                'socialToken' => 'social_token_22222222',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_33333333']
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals('token_33333333', $content['api_key']);
    }

    public function testFailLogin()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => '',
                'lastName' => '',
                'email' => '',
                'socialNetwork' => '',
                'socialToken' => '',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111_invalid']
        );

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testFailLoginForm()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => '', // Email not provided
                'socialNetwork' => '',
                'socialToken' => '',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111']
        );

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testFailValidationLoginForm()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/customers/login',
            [
                'firstName' => 'John(INVALID)',
                'lastName' => 'Doe(INVALID)',
                'email' => 'john.doe(INVALID)example.com',
                'socialNetwork' => '',
                'socialToken' => '',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111']
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
            '/customers/login',
            [
                'firstName' => '',
                'lastName' => '',
                'email' => '',
                'socialNetwork' => 'facebook',
                'socialToken' => 'social_token_11111111_invalid',
            ],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111']
        );

        self::assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testlogoutFailApiKeyToken()
    {
        $client = $this->getClient();
        $client->request(
            'GET',
            '/customers/logout',
            [],
            [],
            ['HTTP_API-Key-Token' => 'token_11111111']
        );

        self::assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testlogoutFailWithoutApiKeyToken()
    {
        $client = $this->getClient();
        $client->request(
            'GET',
            '/customers/logout',
            [],
            [],
            []
        );

        self::assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testlogoutSuccessApiKeyToken()
    {
        $client = $this->getClient();
        $customer = $this->getEm()
            ->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey'=>'token_58e2a426dcddd']);

        self::assertNotNull(null, $customer);
        $client->request(
            'GET',
            '/customers/logout',
            [],
            [],
            ['HTTP_API-Key-Token' => 'token_58e2a426dcddd']
        );

        $customer = $this->getEm()
            ->getRepository('AppBundle:Customer')
            ->findOneBy(['apiKey'=>'token_58e2a426dcddd']);
        self::assertEquals(null, $customer);
        self::assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request(
            'GET',
            '/customers/logout',
            [],
            [],
            ['HTTP_API-Key-Token' => 'token_58e2a426dcddd']
        );

        self::assertEquals(403, $client->getResponse()->getStatusCode());
    }

}
