<?php

namespace AppBundle\Tests\Controller;

class PostsControllerTest extends AbstractApiController
{
    public function testGetPosts()
    {
        $this->request('/posts');
    }

    public function testGetPostsSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Post')->findOneBy([])->getSlug();
        $this->request('/posts/'.$slug);
        $this->request('/posts/nonexistent-slug', 'GET', 404);
    }

    public function testPostsResponseFields()
    {
        $client = $this->getClient();
        $client->request('GET', '/posts');
        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('page', $content);
        self::assertArrayHasKey('total_count', $content);
        self::assertArrayHasKey('posts', $content);
        self::assertArrayHasKey('count', $content);

        self::assertEquals(1, $content['page']);
        self::assertEquals(16, $content['total_count']);
        self::assertCount(10, $content['posts']);
        self::assertEquals(10, $content['count']);

        self::assertArrayHasKey('locale', $content['posts'][0]);
        self::assertArrayHasKey('title', $content['posts'][0]);
        self::assertArrayHasKey('short_description', $content['posts'][0]);
        self::assertArrayHasKey('main_picture', $content['posts'][0]);
        self::assertArrayHasKey('slug', $content['posts'][0]);
        self::assertArrayHasKey('created_at', $content['posts'][0]);
        self::assertArrayHasKey('updated_at', $content['posts'][0]);
        self::assertArrayHasKey('tags', $content['posts'][0]);
        self::assertArrayHasKey('pinned', $content['posts'][0]);
    }

    public function testPinnedPost()
    {
        $client =  $this->getClient();

        $client->request('GET', '/posts');
        $result = $client->getResponse()->getContent();
        $result = json_decode($result);

        $firstPostSlug = $result->posts[0]->slug;
        $secondPostSlug = $result->posts[1]->slug;

        $em = $this->getEm();
        $secondPost = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $secondPostSlug]);
        $secondPost->setPinned(true);
        $em->flush($secondPost);

        $client->request('GET', '/posts');
        $result = $client->getResponse()->getContent();
        $result = json_decode($result);

        $this->assertEquals($secondPostSlug, $result->posts[0]->slug);
        $this->assertEquals($firstPostSlug, $result->posts[1]->slug);

        //cleenup
        $secondPost = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $secondPostSlug]);
        $secondPost->setPinned(false);
        $em->flush($secondPost);
    }
}
