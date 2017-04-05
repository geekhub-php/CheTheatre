<?php

namespace AppBundle\Tests\Controller;

use Sonata\AdminBundle\Exception\ModelManagerException;

class AdminPerformanceEventControllerTest extends AbstractAdminController
{
    public function testPerformanceEventListAction()
    {
        $this->request('/admin/PerformanceEvent/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/PerformanceEvent/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Performance', 'Date Time', 'Venue', 'Action']);
    }

    public function testPerformanceEventCreateAction()
    {
        $this->request('/admin/PerformanceEvent/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/PerformanceEvent/create', 'GET', 200);
    }

    public function testPerformanceEventDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:PerformanceEvent')->findOneBy([]);
        $this->processDeleteAction($object);
    }

    public function testPerformanceEventPostUpdate()
    {
        $this->getEm()->clear();
        $object = $this->getEm()->getRepository('AppBundle:PerformanceEvent')->findOneBy([]);
        $priceCategories = $object->getPriceCategories();
        foreach ($priceCategories as $category) {
            $category->setRows('1-200');
        }
        try {
            $this->getContainer()->get('sonata.admin.performance.event')->postUpdate($object);
        } catch (ModelManagerException $e) {
            $message = 'Error row-place!';
            $this->assertEquals($e->getMessage(), $message);
            return;
        }
    }
}
