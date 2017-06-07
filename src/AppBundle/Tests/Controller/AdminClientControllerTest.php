<?php

namespace AppBundle\Tests\Controller;

class AdminClientControllerTest extends AbstractAdminController
{
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

    public function testClientDeleteAction()
    {
        $object = $this->getEm()->getRepository('AppBundle:Client')->findOneBy([]);
        $this->processDeleteAction($object);
    }
}
