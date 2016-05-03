<?php

namespace AppBundle\Tests\Controller;

class AdminPerformanceControllerTest extends AbstractAdminController
{
    public function testPerformanceListAction()
    {
        $this->request('/admin/Performance/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Performance/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Main Picture', 'Title', 'Type', 'Premiere', 'Festival']);
    }

    public function testPerformanceCreateAction()
    {
        $this->request('/admin/Performance/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Performance/create', 'GET', 200);
    }

    public function testPerformanceDeleteAction()
    {
        $performance = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([]);
        $eventsCount1 = count($this->getEm()->getRepository('AppBundle:PerformanceEvent')->findAll());
        $rolesCount1 = count($this->getEm()->getRepository('AppBundle:Role')->findAll());

        $performanceRolesCount = $performance->getRoles()->count();
        $performanceEventsCount = $performance->getPerformanceEvents()->count();
        $this->processDeleteAction($performance);

        $eventsCount2 = count($this->getEm()->getRepository('AppBundle:PerformanceEvent')->findAll());
        $rolesCount2 = count($this->getEm()->getRepository('AppBundle:Role')->findAll());

        $this->assertEquals($eventsCount1 - $performanceEventsCount, $eventsCount2);
        $this->assertEquals($rolesCount1 - $performanceRolesCount, $rolesCount2);
    }
}
