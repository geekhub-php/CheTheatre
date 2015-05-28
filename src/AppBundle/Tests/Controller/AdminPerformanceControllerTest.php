<?php

namespace AppBundle\Tests\Controller;

class AdminPerformanceControllerTest extends AbstractController
{
    public function testPerformanceListAction()
    {
        $this->request('/admin/Performance/list', 'GET', 302);
        $this->request('/admin/Performance/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Performance/list', 'GET', 200);
    }

    public function testPerformanceCreateAction()
    {
        $this->request('/admin/Performance/create', 'GET', 302);
        $this->request('/admin/Performance/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Performance/create', 'GET', 200);
    }
}
