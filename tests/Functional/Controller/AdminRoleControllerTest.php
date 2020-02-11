<?php

namespace App\Tests\Functional\Controller;

class AdminRoleControllerTest extends AbstractAdminController
{
    public function testRoleListAction()
    {
        $this->request('/admin/Role/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Role/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Title', 'Description', 'Performance', 'Employee', 'Action']);
    }

    public function testRoleCreateAction()
    {
        $this->request('/admin/Role/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Role/create', 'GET', 200);
    }

    public function testRoleDeleteAction()
    {
        $object = $this->getEm()->getRepository('App:Role')->findOneBy([]);
        $this->processDeleteAction($object);
    }
}
