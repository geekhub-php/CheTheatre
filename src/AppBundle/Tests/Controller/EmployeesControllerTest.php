<?php

namespace AppBundle\Tests\Controller;

class EmployeesControllerTest extends AbstractApiController
{
    public function testGetEmployees()
    {
        $this->request('/employees');
    }

    public function testGetEmployeesSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $this->request('/employees/'.$slug);
        $this->request('/employees/nonexistent-slug', 'GET', 404);
    }

    public function testGetEmployeesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $this->request('/employees/'.$slug.'/roles');
        $this->request('/employees/nonexistent-slug/roles', 'GET', 404);
    }

    public function testEmployeesResponseFields()
    {
        $client = $this->getClient();
        $client->request('GET', '/employees');

        $content = json_decode($client->getResponse()->getContent(), true);
        $totalEmployeeCount = count($this->getEm()->getRepository('AppBundle:Employee')->findAll());

        self::assertEquals($totalEmployeeCount, $content['total_count']);
        self::assertEquals(1, $content['page']);
        self::assertEquals(10, $content['count']);
        self::assertCount(10, $content['employees']);
    }
}
