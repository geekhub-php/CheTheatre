<?php

namespace AppBundle\Tests\Controller;

class DefaultControllerTest extends AbstractController
{
    public function testGetHomeAction()
    {
        $this->request('/');
    }
}
