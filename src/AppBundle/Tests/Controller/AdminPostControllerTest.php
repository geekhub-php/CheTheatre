<?php

namespace AppBundle\Tests\Controller;

class AdminPostControllerTest extends AbstractController
{
    public function testPostListAction()
    {
        $this->request('/admin/Post/list', 'GET', 302);
        $this->request('/admin/Post/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }

    public function testPostCreateAction()
    {
        $this->request('/admin/Post/create', 'GET', 302);
        $this->request('/admin/Post/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }
}
