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

    public function testPostListResponseFields()
    {
        $this->restRequest('/api/posts');

        $response = json_decode($this->getSessionClient()->getResponse()->getContent(), true);

        $this->assertEquals(
            count($this->getListFields()),
            count(array_keys($response))
        );

        foreach ($this->getListFields() as $field) {
            $this->assertArrayHasKey($field, $response);
        }

        $firstEntity = array_shift($response['posts']);

        $this->assertEquals(
            count($this->getEntityFields()),
            count(array_keys($firstEntity))
        );

        foreach ($this->getEntityFields() as $field) {
            $this->assertArrayHasKey($field, $firstEntity);
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

    private function getListFields()
    {
        return array (
            '_links',
            'page',
            'total_count',
            'posts',
            'count',
        );
    }

    private function getEntityFields()
    {
        return array (
            'locale',
            'title',
            'short_description',
            'text',
            'main_picture',
            'mainPicture',
            'slug',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'tags',
            'pinned',
        );
    }
}
