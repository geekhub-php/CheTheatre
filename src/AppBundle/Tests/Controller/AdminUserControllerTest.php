<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;

class AdminUserControllerTest extends AbstractAdminController
{
    public function setUp()
    {
        parent::setUp();

        $this
            ->getEm()
            ->getFilters()
            ->disable('softdeleteable');
        $this
            ->getEm()
            ->createQueryBuilder()
            ->update('AppBundle:UserOrder', 'u')
            ->set('u.deletedAt', ':deletedAt')
            ->setParameter('deletedAt', null)
            ->getQuery()
            ->execute();
        $this
            ->getEm()
            ->getFilters()
            ->enable('softdeleteable');
        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:UserOrder', 'u')
            ->getQuery()
            ->execute();
        $this
            ->getEm()
            ->getFilters()
            ->disable('softdeleteable');
        $this
            ->getEm()
            ->createQueryBuilder()
            ->update('AppBundle:User', 'u')
            ->set('u.deletedAt', ':deletedAt')
            ->setParameter('deletedAt', null)
            ->getQuery()
            ->execute();
        $this
            ->getEm()
            ->getFilters()
            ->enable('softdeleteable');
        $this
            ->getEm()
            ->createQueryBuilder()
            ->delete('AppBundle:User', 'u')
            ->getQuery()
            ->execute();
        $user1 = new User();
        $user1
            ->setFirstName('Alex')
            ->setLastName('Alexanrov')
            ->setUsername('userTest1')
            ->setApiKey('token_admin_user1')
            ->setRole('ROLE_API')
            ->setEmail('blabla@gmail.com');
        $user2 = new User();
        $user2
            ->setFirstName('Petro')
            ->setLastName('Petrov')
            ->setUsername('userTest2')
            ->setApiKey('token_admin_user2')
            ->setRole('ROLE_API')
            ->setEmail('bla222@gmail.com');
        $order1 = new UserOrder();
        $order1
            ->setStatus('pending')
            ->setUser($user1);
        $order2 = new UserOrder();
        $order2
            ->setStatus('pending')
            ->setUser($user1);
        $order3 = new UserOrder();
        $order3
            ->setStatus('pending')
            ->setUser($user2);
        $user1->addOrder($order1);
        $user1->addOrder($order2);
        $user2->addOrder($order3);
        $this->getEm()->persist($user1);
        $this->getEm()->persist($user2);
        $this->getEm()->persist($order1);
        $this->getEm()->persist($order2);
        $this->getEm()->persist($order3);
        $this->getEm()->flush();
    }

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
        $countOrders1 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findBy(['deletedAt' => null]));

        $button = $crawler->selectButton('Update');
        $form = $button->form();

        $name = $crawler->filter('select')->eq(0)->attr('name');
        $name = substr($name, 0, 14);
        $values = $form->getPhpValues();
        unset($values[$name]['orders'][0]);
        $this->getClient()->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $countOrders2 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findBy(['deletedAt' => null]));
        $this->assertEquals($countOrders2, $countOrders1 - 1);
    }

    public function testUserDeleteAction()
    {
        $user = $this->getEm()->getRepository('AppBundle:User')->findOneBy([]);
        $userCount1 = count($this->getEm()->getRepository('AppBundle:User')->findAll());
        $orderCount1 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findAll());
        $userOrdersCount1 = count($user->getOrders());
        $this->processDeleteAction($user);

        $orderCount2 = count($this->getEm()->getRepository('AppBundle:UserOrder')->findAll());
        $userCount2 = count($this->getEm()->getRepository('AppBundle:User')->findAll());
        $this->assertEquals($userCount1 - 1, $userCount2);
        $this->assertEquals($orderCount1 - $userOrdersCount1, $orderCount2);
    }
}
