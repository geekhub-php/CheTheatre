<?php

namespace AppBundle\Tests\Controller;

class AdminVenueControllerTest extends AbstractAdminController
{
    public function testVenueCreateAction()
    {
        $this->request('/admin/Venue/create', 'GET', 302);

        $this->logIn();

        $crawler = $this->request('/admin/Venue/create', 'GET', 200);

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
     */
    public function testVenueDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Venue')->findOneBy([]);
        if (count($object->getPerformanceEvents()) == 0) {
            $this->assertFalse($this->getContainer()->get('sonata.admin.venue')->preRemove($object));
            $this->processDeleteAction($object);
        }
    }

    /**
     * @depends testVenueCreateAction
     */
    public function testVenuePreRemove()
    {
        $this->getEm()->clear();
        $object = $this->getEm()->getRepository('AppBundle:Venue')->findOneBy([]);
        if (count($object->getPerformanceEvents()) != 0) {
            try {
                $this->getContainer()->get('sonata.admin.venue')->preRemove($object);
            } catch (\Exception $e) {
                $message = sprintf('An Error has occurred during deletion of item "%s".', $object->getTitle());
                $this->assertEquals($e->getMessage(), $message);
                $this->assertEquals($e->getCode(), 200);
                return;
            }
            $this->fail("Expected Exception has not been raised.");
        }
    }
}
