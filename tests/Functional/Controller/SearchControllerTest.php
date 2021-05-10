<?php


namespace App\Tests\Functional\Controller;


class SearchControllerTest extends AbstractController
{
    public function testSearch()
    {
        $this->restRequest('/api/search?q=а');
    }
}
