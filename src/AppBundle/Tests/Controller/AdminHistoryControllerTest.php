<?php

namespace AppBundle\Tests\Controller;

class AdminHistoryControllerTest extends AbstractController
{
    public function testHistoryListAction()
    {
        $this->request('/admin/History/list', 'GET', 302);
        $this->request('/admin/History/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/History/list', 'GET', 200);
    }

    public function testHistoryCreateAction()
    {
        $this->request('/admin/History/create', 'GET', 302);
        $this->request('/admin/History/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/History/create', 'GET', 200);
    }
}
