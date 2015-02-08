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
 * @RouteResource("Performance")
 */
class PerformancesController extends Controller
{
    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns a collection of Performances",
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *         }
     *     },
     * output = { "class" = "AppBundle\Entity\Performance", "collection" = true, "collectionName" = "Performances" }
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

        $performances = $em->getRepository('AppBundle:Performance')->findAll();

        $restView = View::create();
        $restView
            ->setData($performances)
            ->setHeaders(array(
                    "Content-Type" => "application/json",
                    "Location" => $this->generateUrl('get_performances')
                )
            )
        ;
        return $restView;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance by Slug",
     *
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *         }
     *     },
     *  parameters={
     *      {"name"="Slug", "dataType"="string", "required"=true, "description"="Performance slug"}
     *  },
     * output = { "class" = "AppBundle\Entity\Performance" }
     * )
     *
     * @return Response
     *
     * @RestView
     */
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
            ->setHeaders(array(
                    "Content-Type" => "application/json",
                    "Location" => $this->generateUrl('get_performances').'/'.$performance->getSlug()
                )
            )
        ;
        return $restView;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance by Slug and its Roles",
     *
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *         }
     *     },
     *  parameters={
     *      {"name"="Slug", "dataType"="string", "required"=true, "description"="Performance slug"}
     *  },
     * output = { "class" = "AppBundle\Entity\Role", "collection" = true, "collectionName" = "Roles" }
     * )
     *
     * @return Response
     *
     * @RestView
     */
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
            ->setHeaders(array(
                    "Content-Type" => "application/json",
                    "Location" => $this->generateUrl('get_performances').'/'.$performance->getSlug().'/roles'
                )
            )
        ;
        return $restView;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance by Slug and its Performance Events",
     *
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *         }
     *     },
     *  parameters={
     *      {"name"="Slug", "dataType"="string", "required"=true, "description"="Performance slug"}
     *  },
     * output = { "class" = "AppBundle\Entity\PerformanceEvent", "collection" = true, "collectionName" = "PerformanceEvents" }
     * )
     *
     * @return Response
     *
     * @RestView
     */
    public function getPerformanceeventsAction($slug)
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
            ->setHeaders(array(
                    "Content-Type" => "application/json",
                    "Location" => $this->generateUrl('get_performances').'/'.$performance->getSlug().'/performanceevents'
                )
            )
        ;
        return $restView;
    }
}
