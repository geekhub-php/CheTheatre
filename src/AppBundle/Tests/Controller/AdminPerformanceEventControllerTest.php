<?php

namespace AppBundle\Tests\Controller;

class AdminPerformanceEventControllerTest extends AbstractController
{
    public function testPerformanceEventListAction()
    {
        $this->request('/admin/PerformanceEvent/list', 'GET', 302);
        $this->request('/admin/PerformanceEvent/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/PerformanceEvent/list', 'GET', 200);
    }

    public function testPerformanceEventCreateAction()
    {
        $this->request('/admin/PerformanceEvent/create', 'GET', 302);
        $this->request('/admin/PerformanceEvent/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/PerformanceEvent/create', 'GET', 200);
    }
}
