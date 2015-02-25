<?php

namespace AppBundle\Tests\Controller;

class PerformancesControllerTest extends AbstractController
{
    public function testGetPerformances()
    {
        $this->request('/performances');
    }

    public function testGetPerformancesSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/' . $slug);
        $this->request('/performances/' . base_convert(md5(uniqid()),11,10), 'GET', 404);
    }

    public function testGetPerformancesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/' . $slug . '/roles');
        $this->request('/performances/' . base_convert(md5(uniqid()),11,10) . '/roles', 'GET', 404);
    }

    public function testGetPerformancesSlugPerformanceEvents()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/' . $slug . '/performanceevents');
        $this->request('/performances/' . base_convert(md5(uniqid()),11,10) . '/performanceevents' , 'GET', 404);
    }
}
