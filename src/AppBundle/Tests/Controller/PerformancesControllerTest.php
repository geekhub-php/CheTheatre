<?php

namespace AppBundle\Tests\Controller;

class PerformancesControllerTest extends AbstractController
{
    public function testGetPerformancesList()
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

    public function testPerformancesResponseFields()
    {
        $client = $this->getClient();

        $client->request('GET', '/performances');
        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('page', $content);
        self::assertArrayHasKey('total_count', $content);
        self::assertArrayHasKey('performances', $content);
        self::assertArrayHasKey('count', $content);

        self::assertEquals(1, $content['page']);
        self::assertEquals(21, $content['total_count']);
        self::assertCount(10, $content['performances']);
        self::assertEquals(10, $content['count']);

        self::assertArrayHasKey('locale', $content['performances'][0]);
        self::assertArrayHasKey('title', $content['performances'][0]);
        self::assertArrayHasKey('type', $content['performances'][0]);
        self::assertArrayHasKey('description', $content['performances'][0]);
        self::assertArrayHasKey('premiere', $content['performances'][0]);
        self::assertArrayHasKey('mainPicture', $content['performances'][0]);
        self::assertArrayHasKey('sliderImage', $content['performances'][0]);
        self::assertArrayHasKey('gallery', $content['performances'][0]);
        self::assertArrayHasKey('slug', $content['performances'][0]);
        self::assertArrayHasKey('created_at', $content['performances'][0]);
        self::assertArrayHasKey('updated_at', $content['performances'][0]);
        self::assertArrayHasKey('links', $content['performances'][0]);
    }
}
