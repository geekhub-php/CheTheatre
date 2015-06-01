<?php

namespace AppBundle\Tests\Controller;

class AdminEmployeeControllerTest extends AbstractAdminController
{
    public function testEmployeeListAction()
    {
        $this->request('/admin/Employee/list', 'GET', 302);
        $this->request('/admin/Employee/list' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Employee/list', 'GET', 200);
        $this->assertAdminListPageHasColumns(['Avatar', 'First Name', 'Last Name', 'Dob', 'Position', 'Roles']);
    }

    public function testEmployeeCreateAction()
    {
        $this->request('/admin/Employee/create', 'GET', 302);
        $this->request('/admin/Employee/create' . base_convert(md5(uniqid()), 11, 10), 'GET', 404);

        $this->logIn();

        $this->request('/admin/Employee/create', 'GET', 200);
    }

    public function testEmployeeDeleteAction()
    {
        $this->logIn();
        $employee = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([]);

        $page = $this->request(sprintf('/admin/Employee/%s/delete?tl=en', $employee->getId()), 'GET', 200);
        $form = $this->getConfirmDeleteFormObject($page);

        $this->getClient()->followRedirects(true);
        $listPage = $this->getClient()->submit($form);

        $this->assertContains(
            sprintf('Item "%s" has been deleted successfully.', $employee),
            trim($listPage->filter('.alert-success')->text())
        );
    }
}
