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
 * @RouteResource("PerformanceEvent")
 */
class PerformanceEventsController extends Controller
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

        $performanceEvents = $em->getRepository('AppBundle:PerformanceEvent')->findAll();

        $restView = View::create();
        $restView
            ->setData($performanceEvents)
            ->setStatusCode('200')
            ->setHeaders(array("Content-Type" => "application/json"))
        ;
        return $restView;
    }

    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $performanceEvent = $em->getRepository('AppBundle:PerformanceEvent')->findOneById($id);

        if (!$performanceEvent) {
            throw $this->createNotFoundException('Unable to find '.$id.' entity');
        }

        $restView = View::create();
        $restView
            ->setData($performanceEvent)
            ->setStatusCode('200')
            ->setHeaders(array("Content-Type" => "application/json"))
        ;
        return $restView;
    }
}
