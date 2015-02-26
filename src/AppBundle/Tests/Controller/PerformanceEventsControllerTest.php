<?php

namespace AppBundle\Tests\Controller;

class PerformanceEventsControllerTest extends AbstractController
{
    public function testGetPerformanceEvents()
    {
        $this->request('/performanceevents');
    }

    public function testGetPerformanceEventsId()
    {
        $id = $this->getEm()->getRepository('AppBundle:PerformanceEvent')->findOneBy([])->getId();
        $this->request('/performanceevents/'.$id);
        $this->request('/performanceevents/'.base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }
}
