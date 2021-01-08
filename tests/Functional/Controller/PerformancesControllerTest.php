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
        /** @var Performance $performance */
        $performance = $this->getEm()->getRepository(Performance::class)->findOneBy([]);
        $slug = $performance->getSlug();
        $this->restRequest('/api/performances/'.$slug);

        $eTag = $this->getSessionClient()->getResponse()->headers->get('Etag');
        $this->assertNotNull($eTag);
        $this->restRequest('/api/performances/'.$slug, 'GET', 304, ['HTTP_if_none_match' => $eTag]);

        /** @var Performance $performance */
        $performance = $this->getEm()->find(Performance::class, $performance->getId());
        $performance->setUpdatedAt(new \DateTime());
        $this->getEm()->flush($performance);
        $this->restRequest('/api/performances/'.$slug, 'GET', 200, ['HTTP_if_none_match' => $eTag]);

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
            '_links',
            'page',
            'total_count',
            'performances',
            'count',
        );
    }

    private function getEntityFields()
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
            'created_at',
            'updated_at',
            'links',
        );
    }
}
