<?php

namespace AppBundle\Tests\Controller;

class EmployeesControllerTest extends AbstractController
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

        self::assertEquals(66, $content['total_count']);
        self::assertEquals(1, $content['page']);
        self::assertEquals(10, $content['count']);
        self::assertCount(10, $content['employees']);

        self::assertArrayHasKey('locale', $content['employees'][0]);
        self::assertArrayHasKey('first_name', $content['employees'][0]);
        self::assertArrayHasKey('last_name', $content['employees'][0]);
        self::assertArrayHasKey('dob', $content['employees'][0]);
        self::assertArrayHasKey('position', $content['employees'][0]);
        self::assertArrayHasKey('biography', $content['employees'][0]);
        self::assertArrayHasKey('gallery', $content['employees'][0]);
        self::assertArrayHasKey('slug', $content['employees'][0]);
        self::assertArrayHasKey('avatar', $content['employees'][0]);
        self::assertArrayHasKey('created_at', $content['employees'][0]);
        self::assertArrayHasKey('updated_at', $content['employees'][0]);
    }
}
