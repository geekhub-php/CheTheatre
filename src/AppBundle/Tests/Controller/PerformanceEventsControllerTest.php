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
        $this->request('/performanceevents/100500', 'GET', 404);
    }

    /**
     * @dataProvider providerPerformanceEventsResponseFields
     */
    public function testPerformanceEventsResponseFields($field)
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/performanceevents');

        $this->assertContains($field, $client->getResponse()->getContent());
    }

    public function providerPerformanceEventsResponseFields()
    {
        return [
            ['performance_events'],
            ['id'],
            ['performance'],
            ['title'],
            ['type'],
            ['description'],
            ['premiere'],
            ['mainPicture'],
            ['sliderImage'],
            ['performance_small'],
            ['performance_big'],
            ['slider_small'],
            ['slider_slider'],
            ['reference'],
            ['url'],
            ['properties'],
            ['alt'],
            ['title'],
            ['src'],
            ['width'],
            ['height'],
            ['slug'],
            ['created_at'],
            ['updated_at'],
            ['date_time'],
            ['year'],
            ['month'],
            ['day'],
            ['time'],
            ['count']
        ];
    }
}
