<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testGetBlogArticles()
    {
        $client = static::createClient();
        $client->request('GET', '/blog/articles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorBlogArticles()
    {
        $client = static::createClient();
        $client->request('GET', '/blog/articles');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetBlogArticlesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/blog/articles/{slug}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetErrorBlogArticlesSlug()
    {
        $client = static::createClient();
        $client->request('GET', '/blog/articles/{slug}');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }
}
