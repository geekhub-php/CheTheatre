<?php

namespace AppBundle\Tests\Controller;

class AdminRoleControllerTest extends AbstractController
{
    public function testRoleListAction()
    {
        $this->request('/admin/Role/list', 'GET', 302);
        $this->request('/admin/Role/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }

    public function testRoleCreateAction()
    {
        $this->request('/admin/Role/create', 'GET', 302);
        $this->request('/admin/Role/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }
}
