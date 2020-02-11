<?php

namespace App\Tests\Functional\Controller;

class PerformanceEventsControllerTest extends AbstractController
{
    public function testGetPerformanceEvents()
    {
        $this->restRequest('/api/performanceevents');
    }

    public function testGetPerformanceEventsId()
    {
        $id = $this->getEm()->getRepository('App:PerformanceEvent')->findOneBy([])->getId();
        $this->restRequest('/api/performanceevents/'.$id);
        $this->restRequest('/api/performanceevents/100500', 'GET', 404);
    }

    /**
     * @dataProvider providerPerformanceEventsResponseFields
     */
    public function testPerformanceEventsResponseFields($field)
    {
        $this->restRequest('/api/performanceevents');
        $this->assertContains($field, $this->getSessionClient()->getResponse()->getContent());
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
