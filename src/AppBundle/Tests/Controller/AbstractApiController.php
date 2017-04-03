<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

class AbstractApiController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    protected function request($path, $method = 'GET', $expectedStatusCode = 200, $headers = [])
    {
        $crawler = parent::request($path, $method, $expectedStatusCode, $headers);

        // It is necessary to set RequestContext here because after previous request it is empty.
        $this->getContainer()->get('router')->setContext(
            $this->getRequestContextFromRequest($this->getClient()->getRequest())
        );

        $this->getContainer()->get('app_test.swagger_spec_validator')->assertResource(
            $this->getContainer()->get('router')->matchRequest($this->getClient()->getRequest())['_route'],
            $this->getClient()->getRequest(),
            $this->getClient()->getResponse()
        );

        return $crawler;
    }

    /**
     * @param Request $request
     *
     * @return RequestContext
     */
    private function getRequestContextFromRequest(Request $request)
    {
        $requestContent = $this->getContainer()->get('router')->getContext()->fromRequest($request);

        return $requestContent;
    }
}
