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
        $this->request('/performances/'.$slug);
        $this->request('/performances/'.base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }

    public function testGetPerformancesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/'.$slug.'/roles');
        $this->request('/performances/'.base_convert(md5(uniqid()), 11, 10).'/roles', 'GET', 404);
    }

    public function testGetPerformancesSlugPerformanceEvents()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/'.$slug.'/performanceevents');
        $this->request('/performances/'.base_convert(md5(uniqid()), 11, 10).'/performanceevents', 'GET', 404);
    }

    /**
     * @dataProvider providerPerformancesResponseFields
     */
    public function testPerformancesResponseFields($field)
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/performances');

        $this->assertContains($field, $client->getResponse()->getContent());
    }

    public function providerPerformancesResponseFields()
    {
        return [
            ['performances'],
            ['title'],
            ['type'],
            ['description'],
            ['premiere'],
            ['mainPicture'],
            ['reference'],
            ['performance_small'],
            ['performance_big'],
            ['url'],
            ['properties'],
            ['alt'],
            ['title'],
            ['src'],
            ['width'],
            ['height'],
            ['slug'],
            ['created_at'],
            ['updated_at'],
            ['links'],
            ['rel'],
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
