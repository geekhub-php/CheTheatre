<?php

namespace AppBundle\Tests\Controller;

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
}
