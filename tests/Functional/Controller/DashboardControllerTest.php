<?php

namespace App\Tests\Functional\Controller;

class DashboardControllerTest extends AbstractController
{
    public function testAccesDeniedDasboardAction()
    {
        $this->request('/admin/dashboard', 'GET', 302);

        $client = $this->logIn();

        $crawler = $client->request('GET', '/admin/dashboard');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
