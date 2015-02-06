<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\NoRoute;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Performance")
 */
class PerformancesController extends Controller
{
    /**
     * Collection get action
     * @return Response
     *
     * @RestView
     */
    public function cgetAction()
    {
        $em = $this->getDoctrine()->getManager();

        $performances = $em->getRepository('AppBundle:Performance')->findAll();

        $restView = View::create();
        $restView
            ->setData($performances)
            ->setStatusCode('200')
            ->setHeaders(array("Content-Type" => "application/json"))
        ;
        return $restView;
    }

    public function getAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em->getRepository('AppBundle:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $restView = View::create();
        $restView
            ->setData($performance)
            ->setStatusCode('200')
            ->setHeaders(array("Content-Type" => "application/json"))
        ;
        return $restView;
    }

    public function getRolesAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em->getRepository('AppBundle:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $roles = $performance->getRoles();

        $restView = View::create();
        $restView
            ->setData($roles)
            ->setStatusCode('200')
            ->setHeaders(array("Content-Type" => "application/json"))
        ;
        return $restView;
    }

    public function getEventsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em->getRepository('AppBundle:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $performanceEvents = $performance->getPerformanceEvents();

        $restView = View::create();
        $restView
            ->setData($performanceEvents)
            ->setStatusCode('200')
            ->setHeaders(array("Content-Type" => "application/json"))
        ;
        return $restView;
    }
}
