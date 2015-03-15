<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Model\PerformanceEventsResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @RouteResource("PerformanceEvent")
 */
class PerformanceEventsController extends Controller
{
    const MAX_DAYS_PER_GET = 367;

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns a collection of PerformanceEvents",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when date diff more than 1 year",
     * },
     *  output = "array<AppBundle\Model\PerformanceEventsResponse>"
     * )
     *
     * @QueryParam(name="fromDate", default="today", requirements="\d{2}-\d{2}-\d{4}|today" , description="Find entries from this date, fromat=dd-mm-yyyy")
     * @QueryParam(name="toDate", default="+1 Year", requirements="\d{2}-\d{2}-\d{4}|\+1 Year" , description="Find entries to this date, fromat=dd-mm-yyyy")
     * @QueryParam(name="limit", default="all", requirements="\d+|all" , description="Count of entities in collection")
     * @QueryParam(name="performance", description="Performance slug")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $dateDiff = strtotime($paramFetcher->get('toDate')) - strtotime($paramFetcher->get('fromDate'));

        if (self::MAX_DAYS_PER_GET < abs(floor($dateDiff/(60*60*24)))) {
            throw new BadRequestHttpException(sprintf('You can\'t get more than "%s" days', self::MAX_DAYS_PER_GET));
        }

        $result = $this->getDoctrine()->getManager()->getRepository('AppBundle:PerformanceEvent')
            ->findByDateRangeAndSlug(
                new \DateTime($paramFetcher->get('fromDate')),
                new \DateTime($paramFetcher->get('toDate')),
                $paramFetcher->get('performance')
            )
        ;

        if ('all' != $paramFetcher->get('limit')) {
            $result = array_slice($result, 0, $paramFetcher->get('limit'));
        }

        $performanceEventsResponse = new PerformanceEventsResponse();
        $performanceEventsResponse->setPerformanceEvents($result);
        $performanceEventsResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:PerformanceEvent')->getCount());

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
     *      {"name"="id", "dataType"="string", "required"=true, "description"="PerformanceEvent id"}
     *  },
     *  output = "AppBundle\Entity\PerformanceEvent",
     * deprecated = true
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
