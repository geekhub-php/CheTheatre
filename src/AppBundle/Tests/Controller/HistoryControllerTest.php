<?php

namespace AppBundle\Tests\Controller;

class HistoryControllerTest extends AbstractController
{
    public function testGetHistories()
    {
        $this->request('/histories');
    }

    public function testGetHistoriesSlug()
    {
        $slug = $this->getEm()->getRepository('AppBundle:History')->findOneBy([])->getSlug();
        $this->request('/histories/'.$slug);
        $this->request('/histories/'.base_convert(md5(uniqid()), 11, 10), 'GET', 404);
    }
}
