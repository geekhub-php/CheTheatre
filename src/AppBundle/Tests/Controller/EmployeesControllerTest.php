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
        $this->request('/employees/'.base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }

    public function testGetEmployeesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $this->request('/employees/'.$slug.'/roles');
        $this->request('/employees/'.base_convert(md5(uniqid()), 11, 10).'/roles', 'GET', 404);
    }

    /**
     * @dataProvider providerEmployeesResponseFields
     */
    public function testEmployeesResponseFields($field)
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/employees');

        $this->assertContains($field, $client->getResponse()->getContent());
    }

    public function providerEmployeesResponseFields()
    {
        return [
            ['employees'],
            ['first_name'],
            ['last_name'],
            ['dob'],
            ['position'],
            ['biography'],
            ['slug'],
            ['avatar'],
            ['reference'],
            ['employee_small'],
            ['employee_big'],
            ['url'],
            ['properties'],
            ['alt'],
            ['title'],
            ['src'],
            ['width'],
            ['height'],
            ['created_at'],
            ['updated_at'],
            ['page'],
            ['count'],
            ['total_count'],
            ['_links'],
            ['self'],
            ['first'],
            ['prev'],
            ['next'],
            ['last'],
            ['href'],
        ];
    }
}
