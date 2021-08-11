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

    public function testPerformanceListResponseFields()
    {
        $this->restRequest('/api/performances');
        $response = json_decode($this->getSessionClient()->getResponse()->getContent(), true);

        $this->assertEquals(
            count($this->getListFields()),
            count(array_keys($response))
        );

        foreach ($this->getListFields() as $field) {
            $this->assertArrayHasKey($field, $response);
        }
    }
    public function testOnePerformanceResponseFields()
    {
        $this->restRequest('/api/performances/sieried-ghromu-i-tishi');
        $performance = json_decode($this->getSessionClient()->getResponse()->getContent(), true);

        foreach ($this->getEntityFields() as $field) {
            $this->assertArrayHasKey($field, $performance);
        }
    }

    private function getListFields(): array
    {
        return array (
            '_links',
            'page',
            'total_count',
            'performances',
            'count',
        );
    }

    private function getEntityFields(): array
    {
        return array (
            'locale',
            'title',
            'type',
            'description',
            'premiere',
            'mainPicture',
            'sliderImage',
            'slug',
            'producer',
            'created_at',
            'updated_at',
//            'links',
            'audience',
            'age_limit',
            'duration_in_min',
            'seasons',
        );
    }
}
