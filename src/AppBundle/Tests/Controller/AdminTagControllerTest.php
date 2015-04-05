<?php

namespace AppBundle\Tests\Controller;

class AdminTagControllerTest extends AbstractController
{
    public function testTagListAction()
    {
        $this->request('/admin/Tag/list', 'GET', 302);
        $this->request('/admin/Tag/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }

    public function testTagCreateAction()
    {
        $this->request('/admin/Tag/create', 'GET', 302);
        $this->request('/admin/Tag/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }
}
