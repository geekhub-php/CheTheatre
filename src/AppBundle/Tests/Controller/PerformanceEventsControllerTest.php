<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Ticket;

class PerformanceEventsControllerTest extends AbstractApiController
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(
            [
                'Performance',
                'PerformanceEvent',
                'PriceCategory',
                'Ticket',
            ],
            'Entity'
        );
    }

    public function testGetPerformanceEvents()
    {
        $this->request('/performanceevents');
    }

    /**
     * @return PerformanceEvent|null|object
     */
    public function testGetPerformanceEventsId()
    {
        $performanceEvent = $this
            ->getEm()
            ->getRepository('AppBundle:PerformanceEvent')
            ->findOneBy([], ['dateTime' => 'DESC']);

        $this->request('/performanceevents/'.$performanceEvent->getId());
        $this->request('/performanceevents/100500', 'GET', 404);

        return $performanceEvent;
    }

    public function testGetPerformanceEventsTicketsId()
    {
        $id = $this->getEm()->getRepository(Ticket::class)->findOneBy([])->getPerformanceEventId();
        $this->request('/performanceevents/'.$id.'/tickets');
        $this->request('/performanceevents/100500/tickets', 'GET', 404);
    }

    /**
     * @depends testGetPerformanceEventsId
     * @param PerformanceEvent $performanceEvent
     */
    public function testGetPerformanceEventsIdPriceCategories(PerformanceEvent $performanceEvent)
    {
        $client = $this->getClient();
        $client->request('GET', '/performanceevents/'.$performanceEvent->getId().'/pricecategories');
        $content = json_decode($client->getResponse()->getContent(), true);
        if (!empty($content['id'])) {
            self::assertArrayHasKey('rows', $content[0]);
            if (!empty($content['places'])) {
                self::assertArrayHasKey('places', $content[0]);
            }
            self::assertArrayHasKey('color', $content[0]);
            self::assertArrayHasKey('price', $content[0]);
            self::assertArrayHasKey('venueSector_id', $content[0]);
            self::assertArrayHasKey('title', $content[0]['venueSector_id']);
            self::assertArrayHasKey('slug', $content[0]['venueSector_id']);
        }
        $this->request('/performanceevents/100500/pricecategories', 'GET', 404);
    }

    /**
     * @return array
     */
    public function queryParatemerProvider()
    {
        return [
            'Inspect QueryParatemer default values'  => [
                date("d-m-Y", strtotime("now")),
                date("d-m-Y", strtotime("+1 year")),
                null,
                null,
            ],
            'Inspect QueryParatemer "limit"'  => [
                date("d-m-Y", strtotime("-6 month")),
                date("d-m-Y", strtotime("+6 month")),
                5,
                null,
            ],
            'Inspect QueryParatemer "performance"'  => [
                date("d-m-Y", strtotime("-6 month")),
                date("d-m-Y", strtotime("+6 month")),
                5,
                true,
            ],
            'Inspect QueryParatemer interval "fromDate" "toDate"'  => [
                date("d-m-Y", strtotime("-6 month")),
                date("d-m-Y", strtotime("+6 month")),
                5,
                true,
            ],
        ];
    }

    /**
     * @dataProvider queryParatemerProvider
     */
    public function testPerformanceEventsResponseFields($fromDate, $toDate, $limit, $performanceSlug)
    {
        $client = $this->getClient();

        if ($performanceSlug) {
            $performanceEvent = $this->getEm()->getRepository('AppBundle:PerformanceEvent')
                ->findOneBy([], ['dateTime' => 'DESC']);
            $performanceSlug = $performanceEvent->getPerformance()->getSlug();
            self::assertStringMatchesFormat('%s', $performanceSlug);
        }

        self::assertGreaterThan($fromDate, $toDate);

        $client->request(
            'GET',
            "/performanceevents?fromDate=$fromDate&toDate=$toDate&limit=$limit&performance=$performanceSlug"
        );
        $content = json_decode($client->getResponse()->getContent(), true);

        if ($limit !== null) {
            self::assertInternalType('integer', $limit);
            self::assertLessThanOrEqual($limit, $content['count']);
        }

        self::assertArrayHasKey('performance_events', $content);
        if (!empty($content['performance_events'])) {
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
            if (!empty($content['performance_events'][0]['performance']['mainPicture'])) {
                self::assertArrayHasKey('mainPicture', $content['performance_events'][0]['performance']);
            }
            if (!empty($content['performance_events'][0]['performance']['sliderImage'])) {
                self::assertArrayHasKey('sliderImage', $content['performance_events'][0]['performance']);
            }
            self::assertArrayHasKey('gallery', $content['performance_events'][0]['performance']);
            self::assertArrayHasKey('slug', $content['performance_events'][0]['performance']);
            self::assertArrayHasKey('created_at', $content['performance_events'][0]['performance']);
            self::assertArrayHasKey('updated_at', $content['performance_events'][0]['performance']);
        }
    }
}
