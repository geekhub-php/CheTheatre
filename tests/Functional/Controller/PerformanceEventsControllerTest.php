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

    public function testPerformanceEventsResponseFields()
    {
        $this->restRequest('/api/performanceevents?fromDate=01-02-2020&toDate=29-02-2020');
        $content = $this->getSessionClient()->getResponse()->getContent();
        foreach ($this->getFields() as $field) {
            $this->assertContains($field, $content);
        }
    }

    public function getFields()
    {
        return [
            'performance_events',
            'id',
            'performance',
            'title',
            'type',
            'description',
            'premiere',
            'mainPicture',
            'sliderImage',
            'performance_small',
            'performance_big',
            'slider_small',
            'slider_slider',
            'reference',
            'url',
            'properties',
            'alt',
            'title',
            'src',
            'width',
            'height',
            'slug',
            'created_at',
            'updated_at',
            'date_time',
            'year',
            'month',
            'day',
            'time',
            'count',
        ];
    }
}
