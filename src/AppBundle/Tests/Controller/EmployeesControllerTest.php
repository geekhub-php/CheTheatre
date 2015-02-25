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
        $this->request('/employees/'.base_convert(md5(uniqid()),11,10), 'GET', 404);
    }

    public function testGetEmployeesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Employee')->findOneBy([])->getSlug();
        $this->request('/employees/'.$slug.'/roles');
        $this->request('/employees/'.base_convert(md5(uniqid()),11,10).'/roles', 'GET', 404);
    }
}
