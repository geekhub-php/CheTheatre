<?php

namespace App\Tests\Functional\Controller;

class PostsControllerTest extends AbstractController
{
    public function testGetPosts()
    {
        $this->restRequest('/api/posts');
    }

    public function testGetPostsSlug()
    {
        $slug = $this->getEm()->getRepository('App:Post')->findOneBy([])->getSlug();
        $this->restRequest('/api/posts/'.$slug);
        $this->restRequest('/api/posts/nonexistent-slug', 'GET', 404);
    }

    public function testPostsResponseFields()
    {
        $this->restRequest('/api/posts');
        foreach ($this->getFields() as $field) {
            $this->assertContains($field, $this->getSessionClient()->getResponse()->getContent());
        }
    }

    public function testPinnedPost()
    {
        $client =  $this->getSessionClient();

        $this->restRequest('/api/posts');
        $result = $client->getResponse()->getContent();
        $result = json_decode($result);

        $firstPostSlug = $result->posts[0]->slug;
        $secondPostSlug = $result->posts[1]->slug;

        $em = $this->getEm();
        $secondPost = $em->getRepository('App:Post')->findOneBy(['slug' => $secondPostSlug]);
        $secondPost->setPinned(true);
        $em->flush($secondPost);

        $this->restRequest('/api/posts');
        $result = $client->getResponse()->getContent();
        $result = json_decode($result);

        $this->assertEquals($secondPostSlug, $result->posts[0]->slug);
        $this->assertEquals($firstPostSlug, $result->posts[1]->slug);

        //cleenup
        $secondPost = $em->getRepository('App:Post')->findOneBy(['slug' => $secondPostSlug]);
        $secondPost->setPinned(false);
        $em->flush($secondPost);
    }

    public function getFields()
    {
        return [
            'posts',
            'title',
            'short_description',
            'text',
            'mainPicture',
            'reference',
            'post_small',
            'post_big',
            'url',
            'properties',
            'alt',
            'title',
            'src',
            'width',
            'height',
            'slug',
            'tags',
            'id',
            'created_at',
            'updated_at',
            'page',
            'count',
            'total_count',
            '_links',
            'self',
            'first',
            'prev',
            'next',
            'last',
            'href',
        ];
    }
}
