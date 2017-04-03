<?php

namespace AppBundle\Tests\Controller;

class AdminPriceCategoryControllerTest extends AbstractAdminController
{
    public function testPriceCategoryCreateAction()
    {
        $performanceEvent = $this->getEm()->getRepository('AppBundle:PerformanceEvent')->findOneBy([]);

        $this->request('/admin/PriceCategory/create?performanceEvent_id='.$performanceEvent->getId(), 'GET', 302);

        $this->logIn();

        $crawler = $this
            ->request('/admin/PriceCategory/create?performanceEvent_id='.$performanceEvent->getId(), 'GET', 200);

        $form = $crawler->selectButton('Create')->form();

        $venueSector = $this->getEm()->getRepository('AppBundle:VenueSector')
            ->findOneBy(['venue' => $performanceEvent->getVenue()]);

        parse_str(parse_url($form->getUri(), PHP_URL_QUERY), $parameters);
        $formUniqId = $parameters['uniqid'];

        $form->setValues([
            $formUniqId.'[venueSector]' => $venueSector->getId(),
            $formUniqId.'[color]' => '#0000FF',
            $formUniqId.'[rows]' => '1-5,6,7,10-15',
            $formUniqId.'[places]' => '1-5,6,7,10-15',
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
    }
}
