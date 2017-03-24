<?php

namespace AppBundle\Tests\Controller;

class AdminVenueControllerTest extends AbstractAdminController
{
    public function testVenueListAction()
    {
        $this->request('/admin/Venue/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Venue/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Title', 'Action']);
    }

    public function testVenueCreateAction()
    {
        $this->request('/admin/Venue/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Venue/create', 'GET', 200);
    }

    public function testVenueDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Venue')->findOneBy([]);
        if (count($object->getPerformanceEvents()) == 0) {
            $this->assertFalse($this->getContainer()->get('sonata.admin.venue')->preRemove($object));
            $this->processDeleteAction($object);
        }
    }

    public function testVenuePreRemove()
    {
        $object = $this->getEm()->getRepository('AppBundle:Venue')->findOneBy([]);
        if (count($object->getPerformanceEvents()) != 0) {
            try {
                $this->getContainer()->get('sonata.admin.venue')->preRemove($object);
            } catch (\Exception $e) {
                $message = sprintf('An Error has occurred during deletion of item "%s".', $object->getTitle());
                $this->assertEquals($e->getMessage(), $message);
                $this->assertEquals($e->getCode(), 200);
                return;
            }
            $this->fail("Expected Exception has not been raised.");
        }
    }
}
