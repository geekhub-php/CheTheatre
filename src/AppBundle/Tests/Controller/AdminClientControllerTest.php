<?php

namespace AppBundle\Tests\Controller;

class AdminClientControllerTest extends AbstractAdminController
{

    public function testClientListAction()
    {
        $this->setUp();
        $this->request('/admin/Client/list', 'GET', 302);
        $this->logIn();
        $this->request('/admin/Client/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Ip Adress', 'Count Attempts', 'Banned', 'Action']);
    }

    public function testClientDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Client')->findOneBy([]);
        $this->processDeleteAction($object);
    }
}
