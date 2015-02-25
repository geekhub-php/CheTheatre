<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testGetBlogArticles()
    {
        $this->markTestSkipped();

        $client = static::createClient();
        $client->request('GET', '/blog/articles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorBlogArticles()
    {
        $this->markTestSkipped();

        $client = static::createClient();
        $client->request('GET', '/blog/articles');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetBlogArticlesSlug()
    {
        $this->markTestSkipped();

        $client = static::createClient();
        $client->request('GET', '/blog/articles/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorBlogArticlesSlug()
    {
        $this->markTestSkipped();

        $client = static::createClient();
        $client->request('GET', '/blog/articles/{slug}');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }
}
