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

    public function testPerformancesResponseFields()
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

        $firstEntity = array_shift($response['performances']);

        $this->assertEquals(
            count($this->getEntityFields()),
            count(array_keys($firstEntity))
        );

        foreach ($this->getEntityFields() as $field) {
            $this->assertArrayHasKey($field, $firstEntity);
        }
    }

    private function getListFields()
    {
        return array (
            0 => '_links',
            1 => 'page',
            2 => 'total_count',
            3 => 'performances',
            4 => 'count',
        );
    }

    private function getEntityFields()
    {
        return array (
            0 => 'locale',
            1 => 'title',
            2 => 'type',
            3 => 'description',
            4 => 'premiere',
            5 => 'mainPicture',
            6 => 'sliderImage',
            7 => 'slug',
            8 => 'created_at',
            9 => 'updated_at',
            10 => 'links',
        );
    }
}
