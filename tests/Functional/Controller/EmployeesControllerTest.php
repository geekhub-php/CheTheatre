<?php

namespace App\Tests\Functional\Controller;

class EmployeesControllerTest extends AbstractController
{
    public function testGetEmployees()
    {
        $this->restRequest('/api/employees');
    }

    public function testGetEmployeesSlug()
    {
        $slug = $this->getEm()->getRepository('App:Employee')->findOneBy([])->getSlug();
        $this->restRequest('/api/employees/'.$slug);
        $this->restRequest('/api/employees/nonexistent-slug', 'GET', 404);
    }

    public function testGetEmployeesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('App:Employee')->findOneBy([])->getSlug();
        $this->restRequest('/api/employees/'.$slug.'/roles');
        $this->restRequest('/api/employees/nonexistent-slug/roles', 'GET', 404);
    }

    public function testEmployeesResponseFields()
    {
        $this->restRequest('/api/employees');

        $content = $this->getSessionClient()->getResponse()->getContent();
        foreach ($this->getFields() as $field) {
            $this->assertContains($field, $content);
        }
    }

    public function getFields()
    {
        return [
            'employees',
            'first_name',
            'last_name',
            'dob',
            'position',
            'biography',
            'slug',
            'avatar',
            'reference',
            'employee_small',
            'employee_big',
            'url',
            'properties',
            'alt',
            'title',
            'src',
            'width',
            'height',
            'created_at',
            'updated_at',
            'page',
            'count',
            'total_count',
            '_links',
            'self',
            'first',
            'prev',
            'next',
            'last',
            'href',
        ];
    }
}
