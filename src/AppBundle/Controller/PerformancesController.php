<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Pagerfanta;
use AppBundle\Model\PerformancesResponse;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * @RouteResource("Performance")
 */
class PerformancesController extends Controller
{
    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns a collection of Performances",
     *  statusCodes={
     *      200="Returned when all parameters was correct",
     *      404="Returned when the entity is not found",
     *     },
     *  output = "array<AppBundle\Model\PerformancesResponse>"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $queryBuilder = $this->getDoctrine()->getManager()->getRepository('AppBundle:Performance')->findAll();

        $paginater = new Pagerfanta(new ArrayAdapter($queryBuilder));
        $paginater
            ->setMaxPerPage($paramFetcher->get('limit'))
            ->setCurrentPage($paramFetcher->get('page'))
        ;
        $performancesResponse = new PerformancesResponse();
        $performancesResponse->setPerformances($paginater->getCurrentPageResults());
        $performancesResponse->setPageCount($paginater->getNbPages());

        $nextPage = $paginater->hasNextPage() ?
            $this->generateUrl('get_performances', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')+1,
                )
            ) :
            'false';

        $previsiousPage = $paginater->hasPreviousPage() ?
            $this->generateUrl('get_performances', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')-1,
                )
            ) :
            'false';

        $performancesResponse->setNextPage($nextPage);
        $performancesResponse->setPreviousPage($previsiousPage);

        return $performancesResponse;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance by unique property {slug}",
     *  statusCodes={
     *      200="Returned when Performance was found in database",
     *      404="Returned when Performance was not found in database",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Performance by unique name"}
     *  },
     *  output = "AppBundle\Entity\Performance"
     * )
     *
     * @RestView
     */
    public function getAction($slug)
    {
        $performance = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        return $performance;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance roles by his unique {slug}",
     *  statusCodes={
     *      200="Returned when Performance by slug was found in database",
     *      404="Returned when Performance by slug was not found in database",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Performance unique name"}
     *  },
     *  output = "array<AppBundle\Entity\Role>"
     * )
     *
     * @RestView
     */
    public function getRolesAction($slug)
    {
        $performance = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $roles = $performance->getRoles();

        return $roles;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance events by Performance {slug}",
     *  statusCodes={
     *      200="Returned when Performance by {slug} was found in database",
     *      404="Returned when Performance by {slug} was not found in database",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Performance unique name"}
     *  },
     *  output = "array<AppBundle\Entity\PerformanceEvent>",
     * deprecated = true
     * )
     *
     * @RestView
     */
    public function getPerformanceeventsAction($slug)
    {
        $performance = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $performanceEvents = $performance->getPerformanceEvents();

        return $performanceEvents;
    }
}
