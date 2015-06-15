<?php

namespace AppBundle\Tests\Controller;

class AdminRoleControllerTest extends AbstractAdminController
{
    public function testRoleListAction()
    {
        $this->request('/admin/Role/list', 'GET', 302);
        $this->request('/admin/Role/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Role/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Title', 'Description', 'Performance', 'Employee', 'Action']);
    }

    public function testRoleCreateAction()
    {
        $this->request('/admin/Role/create', 'GET', 302);
        $this->request('/admin/Role/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Role/create', 'GET', 200);
    }

    public function testRoleDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Role')->findOneBy([]);
        $this->processDeleteAction($object);
    }
}
