<?php

namespace AppBundle\Tests\Controller;

class DashboardControllerTest extends AbstractController
{
    public function testAccesDeniedDasboardAction()
    {
        $this->request('/admin/dashboard', 'GET', 302);

        $client = $this->logIn();
        $client->request('GET', '/admin/dashboard');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
