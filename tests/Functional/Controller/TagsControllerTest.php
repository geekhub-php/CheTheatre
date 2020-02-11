<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Tag;

class TagsControllerTest extends AbstractController
{
    public function testGetTagSlug()
    {
        $slug = $this->getEm()->getRepository(Tag::class)->findOneBy([])->getSlug();

        $this->restRequest('/api/tags/'.$slug.'/posts');
        $this->restRequest('/api/tags/nonexistent-tag/posts', 'GET', 404);
    }
}
