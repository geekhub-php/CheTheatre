<?php

namespace AppBundle\Tests\Controller;

class PostsControllerTest extends AbstractController
{
    public function testGetPosts()
    {
        $this->request('/posts');
    }

    public function testGetPostsSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Post')->findOneBy([])->getSlug();
        $this->request('/posts/'.$slug);
        $this->request('/posts/'.base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }

    /**
     * @dataProvider providerPostsResponseFields
     */
    public function testPostsResponseFields($field)
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/posts');

        $this->assertContains($field, $client->getResponse()->getContent());
    }

    public function providerPostsResponseFields()
    {
        return [
            ['posts'],
            ['title'],
            ['short_description'],
            ['text'],
            ['slug'],
            ['created_at'],
            ['updated_at'],
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
