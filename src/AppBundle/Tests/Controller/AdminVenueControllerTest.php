<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Venue;

class AdminVenueControllerTest extends AbstractAdminController
{
    /**
     * @return Venue|null|object
     */
    public function testVenueCreateAction()
    {
        $this->request('/admin/Venue/create', 'GET', 302);

        $this->logIn();

        $crawler = $this->request('/admin/Venue/create', 'GET', 200);

        self::assertEquals(1, $crawler->filter('form')->count());
        $form = $crawler->selectButton('Create')->form();

        parse_str(parse_url($form->getUri(), PHP_URL_QUERY), $parameters);
        $formUniqId = $parameters['uniqid'];

        $form->setValues([
            $formUniqId.'[title]' => 'Grand Opera',
            $formUniqId.'[address]' => 'Place de l\'Opéra, 9th arrondissement, Paris, France',
            $formUniqId.'[hallTemplate]' => '<html></html>',
        ]);

        $this->getClient()->submit($form);
        $crawler = $this->getClient()->followRedirect();

        $successMessage = $crawler->filter('div.alert-success');
        self::assertSame(1, $successMessage->count());
        self::assertContains(
            'Item "Grand Opera" has been successfully created',
            $successMessage->text()
        );

        $venue = $this->getEm()->getRepository('AppBundle:Venue')->findOneBy([], ['id' => 'DESC']);

        return $venue;
    }

    /**
     * @depends testVenueCreateAction
     */
    public function testVenueListAction()
    {
        $this->request('/admin/Venue/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Venue/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Title', 'Action']);
    }

    /**
     * @depends testVenueCreateAction
     * @param Venue $venue
     */
    public function testVenueDeleteAction(Venue $venue)
    {
        if (count($venue->getPerformanceEvents()) == 0) {
            $this->assertFalse($this->getContainer()->get('sonata.admin.venue')->preRemove($venue));
            $this->processDeleteAction($venue);
        }
    }

    /**
     * @depends testVenueCreateAction
     * @param Venue $venue
     */
    public function testVenuePreRemove(Venue $venue)
    {
        $this->getEm()->clear();
        if (count($venue->getPerformanceEvents()) != 0) {
            try {
                $this->getContainer()->get('sonata.admin.venue')->preRemove($venue);
            } catch (\Exception $e) {
                $message = sprintf('An Error has occurred during deletion of item "%s".', $venue->getTitle());
                $this->assertEquals($e->getMessage(), $message);
                $this->assertEquals($e->getCode(), 200);
                return;
            }
        }
    }
}
