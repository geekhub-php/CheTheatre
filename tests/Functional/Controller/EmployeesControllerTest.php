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

    public static function employeesGroupCount(): array
    {
        return [
            ['actors'],
            ['art-core'],
            ['ballet'],
            ['administrative-accounting'],
            ['orchestra'],
            ['art-production'],
            ['deputies'],
            ['epoch'],
            [''],
        ];
    }

    /**
     * @dataProvider employeesGroupCount
     */
    public function testGetEmployeesFilteredByGroup(string $group)
    {
        $this->restRequest('/api/employees?group=' . $group);
        $response = json_decode($this->getSessionClient()->getResponse()->getContent(), true);
        $this->assertGreaterThan(0, $response['employees']);
    }

    public function testEmployeesResponseFields()
    {
        $this->restRequest('/api/employees');
        $response = json_decode($this->getSessionClient()->getResponse()->getContent(), true);
        $firstEntity = array_shift($response['employees']);

        foreach ($this->getEntityFields() as $field) {
            $this->assertArrayHasKey($field, $firstEntity);
        }
    }

    private function getEntityFields(): array
    {
        return [
            'locale',
            'first_name',
            'last_name',
            'dob',
            'position',
            'biography',
            'slug',
            'avatar',
            'staff',
            'created_at',
            'updated_at',
        ];
    }
}
