<?php

namespace AppBundle\Tests\Controller;

class AbstractApiController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    protected function request($path, $method = 'GET', $expectedStatusCode = 200)
    {
        $crawler = parent::request($path, $method, $expectedStatusCode);

        $uri = parse_url($this->getClient()->getRequest()->getRequestUri(), PHP_URL_PATH);
        $this->getContainer()->get('app_test.swagger_spec_validator')->assertResource(
            $this->getContainer()->get('router')->match($uri)['_route'],
            $this->getClient()->getRequest(),
            $this->getClient()->getResponse()
        );

        return $crawler;
    }
}
