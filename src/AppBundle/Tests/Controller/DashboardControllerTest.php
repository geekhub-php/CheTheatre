<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DashboardControllerTest extends AbstractController
{
    public function testAccesDeniedDasboardAction()
    {
        $this->markTestSkipped();

        $this->request('/admin/dashboard', 'GET', 302);

        $client = $this->logIn();

        $crawler = $client->request('GET', '/admin/dashboard');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    private function logIn()
    {
        $session = $this->getContainer()->get('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_SUPER_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $this->getContainer()->get('security.token_storage')->setToken($token);

        $cookie = new Cookie($session->getName(), $session->getId());
        $client = $this->getClient();

        $client->getCookieJar()->set($cookie);

        return $client;
    }
}
