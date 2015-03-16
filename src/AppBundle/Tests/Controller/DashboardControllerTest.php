<?php
namespace AppBundle\Tests\Controller;


class DashboardControllerTest extends AbstractController
{
    public function testAccesDeniedDasboardAction()
    {
        $this->request('/dashboard', 'GET', 401);

        $statusCode = $this->logIn();

        $this->assertEquals(200, $statusCode);
    }

    private function logIn()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => '111111',
        ));

        $client->request('GET', '/dashboard', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => '111111',
        ));

        return $client->getResponse()->getStatusCode();
    }
}
