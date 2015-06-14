<?php

namespace AppBundle\Tests\Controller;

class AdminHistoryControllerTest extends AbstractAdminController
{
    public function testHistoryListAction()
    {
        $this->request('/admin/History/list', 'GET', 302);
        $this->request('/admin/History/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/History/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Main Picture', 'History_Date', 'Title', 'Action', 'Type']);
    }

    public function testHistoryCreateAction()
    {
        $this->request('/admin/History/create', 'GET', 302);
        $this->request('/admin/History/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/History/create', 'GET', 200);
    }

    public function testHistoryDeleteAction()
    {
        $tagsCount1 = count($this->getEm()->getRepository('AppBundle:Tag')->findAll());

        $object = $this->getEm()->getRepository('AppBundle:History')->findOneBy([]);
        $this->processDeleteAction($object);

        $tagsCount2 = count($this->getEm()->getRepository('AppBundle:Tag')->findAll());
        $this->assertEquals($tagsCount2, $tagsCount1);
    }
}
