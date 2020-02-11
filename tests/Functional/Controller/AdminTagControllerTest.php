<?php

namespace App\Tests\Functional\Controller;

class AdminTagControllerTest extends AbstractAdminController
{
    public function testTagListAction()
    {
        $this->request('/admin/Tag/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Tag/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Title', 'Posts']);
    }

    public function testTagCreateAction()
    {
        $this->request('/admin/Tag/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Tag/create', 'GET', 200);
    }

    public function testTagDeleteAction()
    {
        $object = $this->getEm()->getRepository('App:Tag')->findOneBy([]);
        $this->processDeleteAction($object);
    }
}
