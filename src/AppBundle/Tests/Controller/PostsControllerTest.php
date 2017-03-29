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
        $totalCount = count($this->getEm()->getRepository('AppBundle:Post')->findAll());

        self::assertEquals(1, $content['page']);
        self::assertEquals($totalCount, $content['total_count']);
        self::assertCount(10, $content['posts']);
        self::assertEquals(10, $content['count']);
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
