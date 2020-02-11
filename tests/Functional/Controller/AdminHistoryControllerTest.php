<?php

namespace App\Tests\Functional\Controller;

class AdminHistoryControllerTest extends AbstractAdminController
{
    public function testHistoryListAction()
    {
        $this->request('/admin/History/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/History/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Main Picture', 'Year', 'Title', 'Action', 'Type']);
    }

    public function testHistoryCreateAction()
    {
        $this->request('/admin/History/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/History/create', 'GET', 200);
    }

    public function testHistoryDeleteAction()
    {
        $tagsCount1 = count($this->getEm()->getRepository('App:Tag')->findAll());

        $object = $this->getEm()->getRepository('App:History')->findOneBy([]);
        $this->processDeleteAction($object);

        $tagsCount2 = count($this->getEm()->getRepository('App:Tag')->findAll());
        $this->assertEquals($tagsCount2, $tagsCount1);
    }
}
