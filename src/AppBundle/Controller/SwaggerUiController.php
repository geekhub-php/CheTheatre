<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SwaggerUiController extends Controller
{
    /**
     * Render SwaggerUI template.
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render(':SwaggerUI:index.html.twig');
    }
}
