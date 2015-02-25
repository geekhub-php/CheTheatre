<?php

namespace AppBundle\Tests\Controller;

class BlogControllerTest extends AbstractController
{
    public function testGetBlogArticles()
    {
        $this->markTestSkipped();

        $this->request('/blog/articles');
    }

    public function testGetBlogArticlesSlug()
    {
        $this->markTestSkipped();

        $slug = null; // get it from DB
        $this->request('/blog/articles/' . $slug);
        $this->request('/blog/articles/' . base_convert(md5(uniqid()),11,10), 'GET', 404);
    }
}
