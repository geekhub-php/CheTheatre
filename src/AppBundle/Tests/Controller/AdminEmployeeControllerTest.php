<?php

namespace AppBundle\Tests\Controller;

class AdminEmployeeControllerTest extends AbstractAdminController
{
    public function testEmployeeListAction()
    {
        $this->request('/admin/Employee/list', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Employee/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Avatar', 'First Name', 'Last Name', 'Dob', 'Position', 'Roles']);
    }

    public function testEmployeeCreateAction()
    {
        $this->request('/admin/Employee/create', 'GET', 302);

        $this->logIn();

        $this->request('/admin/Employee/create', 'GET', 200);
    }

    public function testEmployeeShowAction()
    {
        $employeeId = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([])->getId();
        $this->request("/admin/Employee/$employeeId/show", 'GET', 302);

        $this->logIn();

        $this->request("/admin/Employee/$employeeId/show", 'GET', 200);
    }

    public function testEmployeeDeleteAction()
    {
        $employee = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([]);

        $employeeCount1 = count($this->getEm()->getRepository('AppBundle:Employee')->findAll());
        $rolesCount1 = count($this->getEm()->getRepository('AppBundle:Role')->findAll());
        $employeeRolesCount = $employee->getRoles()->count();

        $this->processDeleteAction($employee);

        $rolesCount2 = count($this->getEm()->getRepository('AppBundle:Role')->findAll());
        $employeeCount2 = count($this->getEm()->getRepository('AppBundle:Employee')->findAll());

        $this->assertEquals($employeeCount1 - 1, $employeeCount2);
        // All employees roles must be deleted
        $this->assertEquals($rolesCount1 - $employeeRolesCount, $rolesCount2);
    }
}
