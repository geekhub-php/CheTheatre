<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Pagerfanta;
use AppBundle\Model\PerformanceEventsResponse;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * @RouteResource("PerformanceEvent")
 */
class PerformanceEventsController extends Controller
{
    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns a collection of PerformanceEvents",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entities with given limit and offset are not found",
     * },
     *  output = "AppBundle\Model\PerformanceEventsResponse"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="fromDate", default="01-01-2014" , description="Find entries from this date")
     * @QueryParam(name="toDate", default="01-01-2050" , description="Find entries to this date")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $queryBuilder = $this->getDoctrine()->getManager()->getRepository('AppBundle:PerformanceEvent')
            ->findByDateRange(
                new \DateTime($paramFetcher->get('fromDate')),
                new \DateTime($paramFetcher->get('toDate'))
            )
        ;

        $paginater = new Pagerfanta(new ArrayAdapter($queryBuilder));

        $paginater
            ->setMaxPerPage($paramFetcher->get('limit'))
            ->setCurrentPage($paramFetcher->get('page'))
        ;

        $performanceEventsResponse = new PerformanceEventsResponse();
        $performanceEventsResponse->setPerformanceEvents($paginater->getCurrentPageResults());
        $performanceEventsResponse->setPageCount($paginater->getNbPages());

        $nextPage = $paginater->hasNextPage() ?
            $this->generateUrl('get_performanceevents', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')+1,
                )
            ) :
            'false';

        $previsiousPage = $paginater->hasPreviousPage() ?
            $this->generateUrl('get_performanceevents', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')-1,
                )
            ) :
            'false';

        $performanceEventsResponse->setNextPage($nextPage);
        $performanceEventsResponse->setPreviousPage($previsiousPage);

        return $performanceEventsResponse;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns one PerformanceEvent by Id",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entity is not found",
     *  },
     *  parameters={
     *      {"name"="Id", "dataType"="string", "required"=true, "description"="PerformanceEvent Id"}
     *  },
     *  output = "AppBundle\Entity\PerformanceEvent"
     * )
     *
     * @RestView
     */
    public function getAction($id)
    {
        $performanceEvent = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:PerformanceEvent')->findOneById($id);

        if (!$performanceEvent) {
            throw $this->createNotFoundException('Unable to find '.$id.' entity');
        }

        return $performanceEvent;
    }
}
