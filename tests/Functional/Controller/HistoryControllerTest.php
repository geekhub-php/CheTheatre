<?php

namespace App\Tests\Functional\Controller;

class HistoryControllerTest extends AbstractController
{
    public function testGetHistories()
    {
        $this->restRequest('/api/histories');
    }

    public function testGetHistoriesSlug()
    {
        $slug = $this->getEm()->getRepository('App:History')->findOneBy([])->getSlug();
        $this->restRequest('/api/histories/'.$slug);
        $this->restRequest('/api/histories/nonexistent-slug', 'GET', 404);
    }
}
