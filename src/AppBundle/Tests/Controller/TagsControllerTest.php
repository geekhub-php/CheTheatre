<?php

namespace AppBundle\Tests\Controller;

class TagsControllerTest extends AbstractController
{
    public function testGetTagSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:Tag')->findOneBy([])->getSlug();

        $this->request('/tags/'.$slug.'/posts');
        $this->request('/tags/'.base_convert(md5(uniqid()), 11, 10).'/posts', 'GET', 404);
    }
}
