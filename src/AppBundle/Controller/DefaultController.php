<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\FormLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function loginAction(Request $request)
    {
        /** @var Session $session */
        $session = $this->get('session');

        $form = $this->createForm(new FormLoginType());

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render(':default:login.html.twig', [
            'error' => $error,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function swaggerUiAction()
    {
        $docUrl = $this->get('service_container')->getParameter('app_swagger_ui_resource_url');

        if (preg_match('/^(https?:)?\/\//', $docUrl)) {
            // If https://..., http://..., or //...
            $url = $docUrl;
        } elseif (strpos($docUrl, '/') === 0) {
            //If starts with "/", interpret as an asset.
            $url = $this->get('templating.helper.assets')->getUrl($docUrl);
        } else {
            // else, interpret as route-name.
            $url = $this->generateUrl($docUrl, [
                'resource' => $this->get('service_container')->getParameter('app_swagger_ui_static_resource_filename')
            ]);
        }

        $url = rtrim($url, '/');

        return $this->render(':default:swagger_ui.html.twig', [
            'resource_url' => $url
        ]);
    }

    /**
     * @param string $resource
     * @return JsonResponse
     */
    public function swaggerUiStaticResourceAction($resource)
    {
        $dir = $this->get('kernel')->getRootDir() . '/../' . $this->get('service_container')->getParameter('app_swagger_ui_static_resource_dir');

        try {
            $finder = new Finder();
            $files = $finder->in($dir)->files()->name($resource);

            if (count($files) === 0){
                throw new \Exception(sprintf('Cannot find API Documentation: %s', $resource));
            }

            $doc = file_get_contents($dir . $resource);

            return new JsonResponse(json_decode($doc));
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }
}
