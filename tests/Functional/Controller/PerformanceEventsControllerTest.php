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
        $response = json_decode($this->getSessionClient()->getResponse()->getContent(), true);

        foreach ($this->getListFields() as $field) {
            $this->assertArrayHasKey($field, $response, sprintf(
                'Field "%s" is not in list "%s"', $field, implode(", ", array_keys($response))
            ));
        }

        $firstEntity = array_shift($response['performance_events']);

        foreach ($this->getEntityFields() as $field) {
            $this->assertArrayHasKey($field, $firstEntity, sprintf(
                'Field "%s" is not in list "%s"', $field, implode(", ", array_keys($firstEntity))
            ));
        }
    }

    private function getEntityFields()
    {
        return array (
            'performance',
            'date_time',
            'venue',
            'year',
            'month',
            'day',
            'time',
        );
    }

    private function getListFields()
    {
        return array (
            'performance_events',
            'count',
        );
    }
}
