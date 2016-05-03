<?php

namespace AppBundle\Tests\Controller;

class TagsControllerTest extends AbstractController
{
    public function testGetTagSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Tag')->findOneBy([])->getSlug();

        $this->request('/tags/'.$slug.'/posts');
        $this->request('/tags/nonexistent-tag/posts', 'GET', 404);
    }
}
