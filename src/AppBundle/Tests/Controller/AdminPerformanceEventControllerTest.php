<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\PerformanceEvent;
use Sonata\AdminBundle\Exception\ModelManagerException;

class AdminPerformanceEventControllerTest extends AbstractAdminController
{
    /**
     * @return PerformanceEvent|null|object
     */
    public function testPerformanceEventCreateAction()
    {
        $this->request('/admin/PerformanceEvent/create', 'GET', 302);

        $this->logIn();

        $performance = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([]);
        $venue = $this->getEm()->getRepository('AppBundle:Venue')->find(2);

        $crawler = $this->request('/admin/PerformanceEvent/create', 'GET', 200);

        $this->assertEquals(1, $crawler->filter('form')->count());
        $form = $crawler->selectButton('Create')->form();

        parse_str(parse_url($form->getUri(), PHP_URL_QUERY), $parameters);
        $formUniqId = $parameters['uniqid'];

        $form->setValues([
            $formUniqId.'[performance]' => $performance->getId(),
            $formUniqId.'[dateTime]' => '11/10/2016 18:00',
            $formUniqId.'[venue]' => $venue->getId(),
        ]);

        $this->getClient()->submit($form);
        $crawler = $this->getClient()->followRedirect();

        $this->assertEquals(200, $this->getClient()->getResponse()->getStatusCode());

        $successMessage = $crawler->filter('div.alert-success');
        self::assertSame(1, $successMessage->count());
        self::assertContains(
            'has been successfully created',
            $successMessage->text()
        );

        $performanceEvent = $this->getEm()->getRepository('AppBundle:PerformanceEvent')
            ->findOneBy([], ['id' => 'DESC']);

        return $performanceEvent;
    }

    /**
     * @depends testPerformanceEventCreateAction
     * @param PerformanceEvent $performanceEvent
     * @return PerformanceEvent|null|object
     */
    public function testPerformanceEventPriceCategoryCreateAction(PerformanceEvent $performanceEvent)
    {
        $this->request('/admin/PriceCategory/create?performanceEvent_id='.$performanceEvent->getId(), 'GET', 302);

        $this->logIn();

        $crawler = $this
            ->request('/admin/PriceCategory/create?performanceEvent_id='.$performanceEvent->getId(), 'GET', 200);

        self::assertEquals(1, $crawler->filter('form')->count());
        $form = $crawler->selectButton('Create')->form();

        $venueSector = $this->getEm()->getRepository('AppBundle:VenueSector')
            ->findOneBy(['venue' => $performanceEvent->getVenue()]);

        parse_str(parse_url($form->getUri(), PHP_URL_QUERY), $parameters);
        $formUniqId = $parameters['uniqid'];

        $form->setValues([
            $formUniqId.'[venueSector]' => $venueSector->getId(),
            $formUniqId.'[color]' => '#0000FF',
            $formUniqId.'[rows]' => '1-17',
            $formUniqId.'[places]' => '',
            $formUniqId.'[price]' => '100',
            $formUniqId.'[performanceEvent]' => $performanceEvent->getId(),
        ]);
        $this->getClient()->submit($form);

        $crawler = $this->getClient()->followRedirect();

        $successMessage = $crawler->filter('div.alert-success');
        self::assertSame(1, $successMessage->count());
        self::assertContains(
            'Item "PriceCategory" has been successfully created',
            $successMessage->text()
        );

        $performanceEvent = $this->getEm()->getRepository('AppBundle:PerformanceEvent')
            ->findOneBy([], ['id' => 'DESC']);

        return $performanceEvent;
    }

    public function testPerformanceEventListAction()
    {
        $this->request('/admin/PerformanceEvent/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/PerformanceEvent/list', 'GET', 200);
        self::assertAdminListPageHasColumns(['Performance', 'Date Time', 'Venue', 'Action']);
    }

    /**
     * @depends testPerformanceEventCreateAction
     * @param PerformanceEvent $performanceEvent
     */
    public function testPerformanceEventInspectPriceCategories(PerformanceEvent $performanceEvent)
    {
        $adminPerformanceEvent = $this->getContainer()->get('sonata.admin.performance.event');
        $venue = $performanceEvent->getVenue();
        $venueSector = $this->getEm()->getRepository('AppBundle:VenueSector')->findOneBy(['venue' => $venue]);

        /**
         *  Exception Seat without price
         */
        try {
            $adminPerformanceEvent->inspectSeatWithoutPrice($venue);
        } catch (ModelManagerException $e) {
            $message = 'In the hall not all places have price!';
            self::assertSame(1, count($e->getMessage()));
            self::assertEquals($e->getMessage(), $message);
        }

        /**
         *  Exception this $row in Venue not exist
         */
        try {
            $strRows = '1-250';
            $adminPerformanceEvent->getRows($venue, $strRows, $venueSector);
        } catch (ModelManagerException $e) {
            $message = 'Error row!';
            self::assertSame(1, count($e->getMessage()));
            self::assertEquals($e->getMessage(), $message);
        }

        /**
         *  Exception this $row-$place in Venue not exist
         */
        try {
            $strRows = '1';
            $strPlaces = '250';
            $adminPerformanceEvent->getRows($venue, $strRows, $venueSector, $strPlaces);
        } catch (ModelManagerException $e) {
            $message = 'Error row-place!';
            self::assertSame(1, count($e->getMessage()));
            self::assertEquals($e->getMessage(), $message);
        }

        /**
         *  Exception Seat with more than one price
         */
        try {
            $strRows = '1';
            $strPlaces = '1-15,14-17';
            $adminPerformanceEvent->getRows($venue, $strRows, $venueSector, $strPlaces);
        } catch (ModelManagerException $e) {
            $message = 'Error Seat with more than one price!';
            self::assertSame(1, count($e->getMessage()));
            self::assertEquals($e->getMessage(), $message);
        }

        /**
         *  Exception Seat with more than one price
         */
        try {
            $adminPerformanceEvent->inspectSeriesNumber($performanceEvent);
        } catch (ModelManagerException $e) {
            $message = 'Error SeriesNumber blank!';
            self::assertSame(1, count($e->getMessage()));
            self::assertEquals($e->getMessage(), $message);
        }
    }

    /**
     * @depends testPerformanceEventPriceCategoryCreateAction
     * @param PerformanceEvent $performanceEvent
     */
    public function testPerformanceEventUpdate(PerformanceEvent $performanceEvent)
    {
        $adminPerformanceEvent = $this->getContainer()->get('sonata.admin.performance.event');

        $performanceEvent->setSeriesNumber('00000055');
        $this->getEm()->persist($performanceEvent);

        self::assertNotFalse($adminPerformanceEvent->preUpdate($performanceEvent));
        self::assertNotFalse($adminPerformanceEvent->postUpdate($performanceEvent));
    }

    /**
     * @depends testPerformanceEventCreateAction
     * @param PerformanceEvent $performanceEvent
     */
    public function testPerformanceEventDeleteAction(PerformanceEvent $performanceEvent)
    {
        $this->processDeleteAction($performanceEvent);
    }
}
