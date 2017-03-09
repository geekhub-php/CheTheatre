<?php

namespace AppBundle\Controller;

use Buzz\Message\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $docFileUrl = $this->generateUrl('swagger_ui_doc_file');

        return $this->render(':SwaggerUI:index.html.twig', [
            'doc_file_url' => $docFileUrl
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function docFileAction()
    {
        $dir = sprintf(
            '%s/../%s',
            $this->get('kernel')->getRootDir(),
            'doc/'
        );

        $docFile = json_decode(file_get_contents($dir . 'theatre.json'), true);
        $docFile['host'] = $this->container->get('router')->getContext()->getHost();

        return new JsonResponse($docFile);
    }
}
