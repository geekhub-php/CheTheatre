<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Ticket;

class PerformanceEventsControllerTest extends AbstractApiController
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

    public function testGetPerformanceEventsTicketsId()
    {
//        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getPerformanceEventId();
//        $this->request('/performanceevents/'.$id.'/tickets');
//        $this->request('/performanceevents/100500/tickets', 'GET', 404);
    }

    public function testGetPerformanceEventsIdPriceCategories()
    {
        $id = $this->getEm()->getRepository('AppBundle:PerformanceEvent')->findOneBy([])->getId();
        $this->request('/performanceevents/'.$id.'/pricecategories');
        $this->request('/performanceevents/100500/pricecategories', 'GET', 404);
    }

    public function testPerformanceEventsResponseFields()
    {
        $client = $this->getClient();

        $client->request('GET', '/performanceevents');
        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('performance_events', $content);
        self::assertArrayHasKey('locale', $content['performance_events'][0]);
        self::assertArrayHasKey('id', $content['performance_events'][0]);
        self::assertArrayHasKey('performance', $content['performance_events'][0]);
        self::assertArrayHasKey('date_time', $content['performance_events'][0]);
        self::assertArrayHasKey('venue', $content['performance_events'][0]);
        self::assertArrayHasKey('year', $content['performance_events'][0]);
        self::assertArrayHasKey('month', $content['performance_events'][0]);
        self::assertArrayHasKey('day', $content['performance_events'][0]);
        self::assertArrayHasKey('time', $content['performance_events'][0]);
        self::assertArrayHasKey('created_at', $content['performance_events'][0]);
        self::assertArrayHasKey('updated_at', $content['performance_events'][0]);
        self::assertArrayHasKey('locale', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('title', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('type', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('description', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('premiere', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('mainPicture', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('sliderImage', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('gallery', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('slug', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('created_at', $content['performance_events'][0]['performance']);
        self::assertArrayHasKey('updated_at', $content['performance_events'][0]['performance']);
    }
}
