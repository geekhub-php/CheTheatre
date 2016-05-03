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
        $this->request('/posts/nonexistent-slug', 'GET', 404);
    }

    /**
     * @dataProvider providerPostsResponseFields
     */
    public function testPostsResponseFields($field)
    {
        $client = $this->getClient();
        $client->request('GET', '/posts');

        $this->assertContains($field, $client->getResponse()->getContent());
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
        $em->flush();

        $client->request('GET', '/posts');
        $result = $client->getResponse()->getContent();
        $result = json_decode($result);

        $this->assertEquals($secondPostSlug, $result->posts[0]->slug);
        $this->assertEquals($firstPostSlug, $result->posts[1]->slug);

        //cleenup
        $secondPost->setPinned(false);
        $em->flush();
    }

    public function providerPostsResponseFields()
    {
        return [
            ['posts'],
            ['title'],
            ['short_description'],
            ['text'],
            ['mainPicture'],
            ['reference'],
            ['post_small'],
            ['post_big'],
            ['url'],
            ['properties'],
            ['alt'],
            ['title'],
            ['src'],
            ['width'],
            ['height'],
            ['slug'],
            ['tags'],
            ['id'],
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
