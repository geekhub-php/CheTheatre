<?php

namespace AppBundle\Tests\Controller;

class AdminTagControllerTest extends AbstractAdminController
{
    public function testTagListAction()
    {
        $this->request('/admin/Tag/list', 'GET', 302);
        $this->request('/admin/Tag/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Tag/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Title', 'Posts']);
    }

    public function testTagCreateAction()
    {
        $this->request('/admin/Tag/create', 'GET', 302);
        $this->request('/admin/Tag/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Tag/create', 'GET', 200);
    }

    public function testTagDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Tag')->findOneBy([]);
        $this->processDeleteAction($object);
    }
}
