<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\NoRoute;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("PerformanceEvent")
 */
class PerformanceEventsController extends Controller
{
    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns a collection of PerformancesEvents",
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the user is not found",
     *           "Returned when something else is not found"
     *         }
     *     },
     * output = { "class" = "AppBundle\Entity\PerformanceEvent", "collection" = true, "collectionName" = "PerformanceEvents" }
     * )
     *
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
            ->setHeaders(array(
                    "Content-Type" => "application/json",
                    "Location" => $this->generateUrl('get_performanceevents')
                )
            )
        ;
        return $restView;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns PerformancesEvent by Id",
     *
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *           "Returned when something else is not found"
     *         }
     *     },
     *  parameters={
     *      {"name"="Id", "dataType"="string", "required"=true, "description"="PerformanceEvent Id"}
     *  },
     * output = { "class" = "AppBundle\Entity\PerformanceEvent" }
     * )
     *
     * @return Response
     *
     * @RestView
     */
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
            ->setHeaders(array(
                    "Content-Type" => "application/json",
                    "Location" => $this->generateUrl('get_performanceevents').'/'.$performanceEvent->getId()
                )
            )
        ;
        return $restView;
    }
}
