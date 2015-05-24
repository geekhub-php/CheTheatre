<?php

namespace AppBundle\Tests\Controller;

class AdminEmployeeControllerTest extends AbstractController
{
    public function testEmployeeListAction()
    {
        $this->request('/admin/Employee/list', 'GET', 302);
        $this->request('/admin/Employee/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Employee/list', 'GET', 200);
    }

    public function testEmployeeCreateAction()
    {
        $this->request('/admin/Employee/create', 'GET', 302);
        $this->request('/admin/Employee/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Employee/create', 'GET', 200);
    }
}
