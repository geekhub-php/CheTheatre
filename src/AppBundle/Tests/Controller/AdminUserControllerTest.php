<?php

namespace AppBundle\Tests\Controller;

class AdminUserControllerTest extends AbstractAdminController
{
    public function getUserBd()
    {
        $user = $this->getEm()->getRepository('AppBundle:UserOrder')->findOneBy([])->getUser();

        return $user;
    }

    public function testIdOrderInListTemlete()
    {
        $this->logIn();
        $this->request('/admin/User/list', 'GET', 200);
        $idOrder = $this->getEm()->getRepository('AppBundle:UserOrder')->findOneBy([])->getId();
        $crawler = $this->getClient()->getCrawler();
        $resultId = $crawler->filter("td:contains('$idOrder')")
            ->eq(0)
            ->attr('objectid');

        $this->assertEquals($this->getUserBd()->getId(), $resultId);
    }

    public function testUserListAction()
    {
        $this->request('/admin/User/list', 'GET', 302);
        $this->logIn();
        $this->request('/admin/User/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Email', 'First Name', 'Last Name', 'Orders', 'Action']);
    }

    public function testUserOrderDeleteInFormAction()
    {
        $this->logIn();
        $this->request('/admin/User/'.$this->getUserBd()->getId().'/edit', 'GET', 200);
        $crawler = $this->getClient()->getCrawler();
        $countOrders1 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findAll());

        $button = $crawler->selectButton('Update');
        $form = $button->form();

        $name = $crawler->filter('select')->eq(0)->attr('name');
        $name = substr($name, 0, 14);
        $values = $form->getPhpValues();
        unset($values[$name]['orders'][0]);
        $this->getClient()->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $countOrders2 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findAll());
        $this->assertEquals($countOrders2, $countOrders1 - 1);
    }

    public function testUserDeleteAction()
    {
        $user = $this->getEm()->getRepository('AppBundle:User')->findOneBy([]);

        $userCount1 = count($this->getEm()->getRepository('AppBundle:User')->findAll());
        $orderCount1 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findAll());
        $userOrdersCount1 = $user->getOrders()->count();

        $this->processDeleteAction($user);

        $orderCount2 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findAll());
        $userCount2 = count($this->getEm()->getRepository('AppBundle:User')->findAll());

        $this->assertEquals($userCount1 - 1, $userCount2);
        $this->assertEquals($orderCount1 - $userOrdersCount1, $orderCount2);
    }
}
