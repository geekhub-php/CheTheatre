<?php

namespace App\Tests\Functional\Controller;

class AdminEmployeeControllerTest extends AbstractAdminController
{
    public function testEmployeeListAction()
    {
        $this->request('/admin/Employee/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Employee/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Avatar', 'First Name', 'Last Name', 'Dob', 'Position', 'Staff', 'Roles', 'Порядок відображення', 'Action']);
    }

    public function testEmployeeCreateAction()
    {
        $this->request('/admin/Employee/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Employee/create', 'GET', 200);
    }

    public function testEmployeeDeleteAction()
    {
        $employee = $this->getEm()->getRepository('App:Employee')->findOneBy([]);

        $employeeCount1 = count($this->getEm()->getRepository('App:Employee')->findAll());
        $rolesCount1 = count($this->getEm()->getRepository('App:Role')->findAll());
        $employeeRolesCount = $employee->getRoles()->count();

        $this->processDeleteAction($employee);

        $rolesCount2 = count($this->getEm()->getRepository('App:Role')->findAll());
        $employeeCount2 = count($this->getEm()->getRepository('App:Employee')->findAll());

        $this->assertEquals($employeeCount1 - 1, $employeeCount2);
        // All employees roles must be deleted
        $this->assertEquals($rolesCount1 - $employeeRolesCount, $rolesCount2);
    }
}
