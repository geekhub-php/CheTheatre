<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Performance;

class PerformancesControllerTest extends AbstractController
{
    public function testGetPerformances()
    {
        $allPerformances = $this->getEm()->getRepository(Performance::class)->findAll();
        $repertoryPerformances = $this->getEm()->getRepository('App:Performance')->findBy(['festival' => null]);

        $this->restRequest('/api/performances?limit=100500');
        $response = $this->getSessionClient()->getResponse()->getContent();
        $response = json_decode($response);

        $this->assertEquals($response->count, count($response->performances));
        $this->assertEquals(count($repertoryPerformances), $response->count);
        // because of festival performances
        $this->assertNotEquals(count($allPerformances), $response->count);
    }

    public function testGetPerformancesSlug()
    {
        $slug = $this->getEm()->getRepository('App:Performance')->findOneBy([])->getSlug();
        $this->restRequest('/api/performances/'.$slug);

        $eTag = $this->getSessionClient()->getResponse()->headers->get('Etag');
        $this->assertNotNull($eTag);
        $this->restRequest('/api/performances/'.$slug, 'GET', 304, ['HTTP_if_none_match' => $eTag]);

        $this->restRequest('/api/performances/nonexistent-slug', 'GET', 404);
    }

    public function testGetPerformancesSlugRoles()
    {
        $slug = $this->getEm()->getRepository('App:Performance')->findOneBy([])->getSlug();
        $this->restRequest('/api/performances/'.$slug.'/roles');
        $this->restRequest('/api/performances/nonexistent-slug/roles', 'GET', 404);
    }

    public function testGetPerformancesSlugPerformanceEvents()
    {
        $slug = $this->getEm()->getRepository('App:Performance')->findOneBy([])->getSlug();
        $this->restRequest('/api/performances/'.$slug.'/performanceevents');
        $this->restRequest('/api/performances/nonexistent-slug/performanceevents', 'GET', 404);
    }

    public function testPerformancesResponseFields()
    {
        $this->restRequest('/api/performances');
        $content = $this->getSessionClient()->getResponse()->getContent();
        foreach ($this->getFields() as $field) {
            $this->assertContains($field, $content);
        }
    }

    public function getFields()
    {
        return [
            'performances',
            'title',
            'type',
            'description',
            'premiere',
            'mainPicture',
            'sliderImage',
            'performance_small',
            'performance_big',
            'slider_small',
            'slider_slider',
            'reference',
            'url',
            'properties',
            'alt',
            'title',
            'src',
            'width',
            'height',
            'slug',
            'created_at',
            'updated_at',
            'links',
            'rel',
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
