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
}
