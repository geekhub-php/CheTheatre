<?php

namespace AppBundle\Tests\Controller;

class PerformancesControllerTest extends AbstractController
{
    public function testGetPerformances()
    {
        $allPerformances = $this->getEm()->getRepository('AppBundle:Performance')->findAll();
        $repertoryPerformances = $this->getEm()->getRepository('AppBundle:Performance')->findBy(['festival' => null]);

        $this->request('/performances?limit=100500');
        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response);

        $this->assertEquals($response->count, count($response->performances));
        $this->assertEquals(count($repertoryPerformances), $response->count);
        $this->assertNotEquals(count($allPerformances), $response->count);
    }

    public function testGetPerformancesSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/'.$slug);
        $this->request('/performances/nonexistent-slug', 'GET', 404);
    }

    public function testGetPerformancesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/'.$slug.'/roles');
        $this->request('/performances/nonexistent-slug/roles', 'GET', 404);
    }

    public function testGetPerformancesSlugPerformanceEvents()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Performance')->findOneBy([])->getSlug();
        $this->request('/performances/'.$slug.'/performanceevents');
        $this->request('/performances/nonexistent-slug/performanceevents', 'GET', 404);
    }

    /**
     * @dataProvider providerPerformancesResponseFields
     */
    public function testPerformancesResponseFields($field)
    {
        $client = $this->getClient();

        $client->request('GET', '/performances');

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
            ['sliderImage'],
            ['performance_small'],
            ['performance_big'],
            ['slider_small'],
            ['slider_slider'],
            ['reference'],
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
