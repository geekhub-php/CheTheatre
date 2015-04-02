<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use AppBundle\Model\PerformanceEventsResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @RouteResource("PerformanceEvent")
 * @Cache(smaxage="86400", public=true)
 */
class PerformanceEventsController extends Controller
{
    const MAX_DAYS_PER_GET = 367;

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns a collection of theatre performanceEvents",
     *  statusCodes={
     *      200="Returned when all parameters were correct",
     *      400="Returned when date diff more than 1 year",
     * },
     *  output = "array<AppBundle\Model\PerformanceEventsResponse>"
     * )
     *
     * @QueryParam(name="fromDate", default="today", requirements="\d{2}-\d{2}-\d{4}|today" , description="Find entries from this date, fromat=dd-mm-yyyy")
     * @QueryParam(name="toDate", default="+1 Year", requirements="\d{2}-\d{2}-\d{4}|\+1 Year" , description="Find entries to this date, fromat=dd-mm-yyyy")
     * @QueryParam(name="performance", description="Performance slug")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $dateDiff = strtotime($paramFetcher->get('toDate')) - strtotime($paramFetcher->get('fromDate'));

        if (self::MAX_DAYS_PER_GET < abs(floor($dateDiff/(60*60*24)))) {
            throw new BadRequestHttpException(sprintf('You can\'t get more than "%s" days', self::MAX_DAYS_PER_GET));
        }

        $performanceEvents = $em->getRepository('AppBundle:PerformanceEvent')
            ->findByDateRangeAndSlug(
                new \DateTime($paramFetcher->get('fromDate')),
                new \DateTime($paramFetcher->get('toDate')),
                $paramFetcher->get('performance')
            )
        ;

        $performanceEventsTranslated = [];

        foreach ($performanceEvents as $performanceEvent) {

            $performanceEvent->setLocale($paramFetcher->get('locale'));
            $em->refresh($performanceEvent);

            $performanceEvent->getPerformance()->setLocale($paramFetcher->get('locale'));
            $em->refresh($performanceEvent->getPerformance());

            if ($performanceEvent->getTranslations()){
                $performanceEvent->unsetTranslations();
            }

            if ($performanceEvent->getPerformance()->getTranslations()){
                $performanceEvent->getPerformance()->unsetTranslations();
            }

            $performanceEventsTranslated[] = $performanceEvent;
        }

        $performanceEvents = $performanceEventsTranslated;

        $performanceEventsResponse = new PerformanceEventsResponse();
        $performanceEventsResponse->setPerformanceEvents($performanceEvents);

        return $performanceEventsResponse;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns one PerformanceEvent by Id",
     *  statusCodes={
     *      200="Returned when PerformanceEvent by id was found in database",
     *      404="Returned when PerformanceEvent by id was not found id database",
     *  },
     *  parameters={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="PerformanceEvent id"}
     *  },
     *  output = "AppBundle\Entity\PerformanceEvent",
     * deprecated = true
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function getAction(ParamFetcher $paramFetcher, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $performanceEvent = $em->getRepository('AppBundle:PerformanceEvent')->findOneById($id);

        if (!$performanceEvent) {
            throw $this->createNotFoundException('Unable to find '.$id.' entity');
        }

        $performanceEvent->setLocale($paramFetcher->get('locale'));
        $em->refresh($performanceEvent);

        $performanceEvent->getPerformance()->setLocale($paramFetcher->get('locale'));
        $em->refresh($performanceEvent->getPerformance());

        if ($performanceEvent->getTranslations()) {
            $performanceEvent->unsetTranslations();
        }

        if ($performanceEvent->getPerformance()->getTranslations()) {
            $performanceEvent->getPerformance()->unsetTranslations();
        }

        return $performanceEvent;
    }
}
