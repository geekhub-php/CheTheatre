<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Client;

class AdminClientControllerTest extends AbstractAdminController
{
    public function setUp()
    {
        parent::setUp();

        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:Client', 'c')
            ->getQuery()
            ->execute();

        $clientDb = new Client();
        $clientDb
            ->setIp('10.10.10.10')
            ->setCountAttempts(2)
            ->setBanned(false);
        $this->getEm()->persist($clientDb);
        $this->getEm()->flush();
    }

    public function testClientListAction()
    {
        $this->request('/admin/Client/list', 'GET', 302);
        $this->logIn();
        $this->request('/admin/Client/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Ip Adress', 'Count Attempts', 'Banned', 'Action']);
    }

    public function testClientDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Client')->findOneBy([]);
        $this->processDeleteAction($object);
        dump($object);
    }
}
