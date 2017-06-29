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
             ->createQuery('DELETE AppBundle:Client')
             ->execute();
        $this
             ->getEm()
             ->createQueryBuilder()
            ->delete('AppBundle:Client', 'c')
             ->getQuery()
            ->execute();

        $clientDb = new Client();
        $clientDb
             ->setIp('200.200.200.200')
             ->setCountAttempts(2)
             ->setBanned(false);

        $clientDb1 = new Client();
        $clientDb1
             ->setIp('201.201.201.201')
             ->setCountAttempts(2)
             ->setBanned(true);

        $this->getEm()->persist($clientDb1);
        $this->getEm()->persist($clientDb);
        $this->getEm()->flush();
    }

    public function countLockUnlockClients($banned)
    {
        return count(
            $this
                ->getEm()
                ->getRepository('AppBundle:Client')
                ->findBy(['banned' => $banned])
        )
        ;
    }

    public function testClientListAction()
    {
        $this->request('/admin/Client/list', 'GET', 302);
        $this->logIn();
        $this->request('/admin/Client/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Ip Adress', 'Count Attempts', 'Banned', 'Action']);
    }

    public function testLockUnlock()
    {
        $this->logIn();
        $this->request('/admin/Client/list', 'GET', 200);
        $crawler = $this->getClient()->getCrawler();

        $resultNotBanned1 = $this->countLockUnlockClients(false);
        $resultIsBanned1 = $this->countLockUnlockClients(true);
        $link = $crawler->filter('a:contains(" lock ")')->eq(0)->link();
        $this->getClient()->click($link);
        $resultNotBanned2 = $this->countLockUnlockClients(false);
        $resultIsBanned2 = $this->countLockUnlockClients(true);

        $this->assertEquals($resultNotBanned2, $resultNotBanned1 - 1);
        $this->assertEquals($resultIsBanned2, $resultIsBanned1 + 1);

        $link = $crawler->filter('a:contains(" unlock ")')->eq(0)->link();
        $this->getClient()->click($link);
        $resultNotBanned3 = $this->countLockUnlockClients(false);
        $resultIsBanned3 = $this->countLockUnlockClients(true);

        $this->assertEquals($resultNotBanned3, $resultNotBanned2 + 1);
        $this->assertEquals($resultIsBanned3, $resultIsBanned2 - 1);
    }
}
