<?php

namespace App\Tests\Functional\Controller;

class AdminPostControllerTest extends AbstractAdminController
{
    public function testPostListAction()
    {
        $this->request('/admin/Post/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Post/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Main Picture', 'Title', 'Created At', 'Action']);
    }

    public function testPostCreateAction()
    {
        $this->request('/admin/Post/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Post/create', 'GET', 200);
    }

    public function testPostDeleteAction()
    {
        $tagsCount1 = count($this->getEm()->getRepository('App:Tag')->findAll());

        $object = $this->getEm()->getRepository('App:Post')->findOneBy([]);
        $this->processDeleteAction($object);

        $tagsCount2 = count($this->getEm()->getRepository('App:Tag')->findAll());
        $this->assertEquals($tagsCount2, $tagsCount1);
    }
}
