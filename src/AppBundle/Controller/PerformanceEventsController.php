<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Ticket;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Model\PerformanceEventsResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @RouteResource("PerformanceEvent")
 */
class PerformanceEventsController extends Controller
{
    const MAX_DAYS_PER_GET = 367;

    /**
     * @Get("/performanceevents")
     * @QueryParam(
     *     name="fromDate",
     *     default="today",
     *     requirements="\d{2}-\d{2}-\d{4}|today" ,
     *     description="Find entries from this date, fromat=dd-mm-yyyy"
     * )
     * @QueryParam(
     *     name="toDate",
     *     default="+1 Year",
     *     requirements="\d{2}-\d{2}-\d{4}|\+1 Year",
     *     description="Find entries to this date, fromat=dd-mm-yyyy"
     * )
     * @QueryParam(name="limit", description="Count of entities in collection")
     * @QueryParam(name="performance", description="Performance slug")
     * @QueryParam(
     *     name="locale",
     *     requirements="uk|en",
     *     default="uk",
     *     description="Selects language of data you want to receive"
     * )
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
                $paramFetcher->get('limit'),
                $paramFetcher->get('performance')
            )
        ;

        $performanceEventsTranslated = [];

        foreach ($performanceEvents as $performanceEvent) {

            /** @var PerformanceEvent $performanceEvent */
            $performanceEvent->setLocale($paramFetcher->get('locale'));
            $em->refresh($performanceEvent);

            $performanceEvent->getPerformance()->setLocale($paramFetcher->get('locale'));
            $em->refresh($performanceEvent->getPerformance());

            $performanceEvent->getVenue()->setLocale($paramFetcher->get('locale'));
            $em->refresh($performanceEvent->getVenue());

            if ($performanceEvent->getTranslations()) {
                $performanceEvent->unsetTranslations();
            }

            if ($performanceEvent->getPerformance()->getTranslations()) {
                $performanceEvent->getPerformance()->unsetTranslations();
            }

            if ($performanceEvent->getVenue()->getTranslations()) {
                $performanceEvent->getVenue()->unsetTranslations();
            }
            $performanceEventsTranslated[] = $performanceEvent;
        }

        $performanceEvents = $performanceEventsTranslated;

        $performanceEventsResponse = new PerformanceEventsResponse();
        $performanceEventsResponse->setPerformanceEvents($performanceEvents);

        return $performanceEventsResponse;
    }

    /**
     * @Get("/performanceevents/{id}", requirements={"id" = "\d+"})
     * @ParamConverter("performanceEvent", class="AppBundle:PerformanceEvent")
     * @QueryParam(
     *     name="locale",
     *     requirements="uk|en",
     *     default="uk",
     *     description="Selects language of data you want to receive"
     * )
     * @QueryParam(
     *     name="id",
     *     requirements="\d+",
     *     description="PerformanceEvent ID"
     * )
     * @RestView
     */
    public function getAction(ParamFetcher $paramFetcher, PerformanceEvent $performanceEvent)
    {
        $em = $this->getDoctrine()->getManager();

        $performanceEvent->setLocale($paramFetcher->get('locale'));
        $em->refresh($performanceEvent);

        $performanceEvent->getPerformance()->setLocale($paramFetcher->get('locale'));
        $em->refresh($performanceEvent->getPerformance());

        $performanceEvent->getVenue()->setLocale($paramFetcher->get('locale'));
        $em->refresh($performanceEvent->getVenue());

        if ($performanceEvent->getTranslations()) {
            $performanceEvent->unsetTranslations();
        }

        if ($performanceEvent->getPerformance()->getTranslations()) {
            $performanceEvent->getPerformance()->unsetTranslations();
        }

        if ($performanceEvent->getVenue()->getTranslations()) {
            $performanceEvent->getVenue()->unsetTranslations();
        }

        return $performanceEvent;
    }

    /**
     * @Get(requirements={"id" = "\d+"})
     * @RestView(serializerGroups={"get_ticket"})
     * @ParamConverter("id", class="AppBundle:PerformanceEvent")
     */
    public function cgetTicketsAction(PerformanceEvent $id)
    {
        //This done not in right way (PerformanceEvent $performanceEvent)
        // to have RESTfully looking route: /performanceevents/{id}/tickets
        $performanceEvent = $id;

        $em = $this->getDoctrine()->getManager();
        $tickets = $em
            ->getRepository(Ticket::class)
            ->findBy(['performanceEvent' => $performanceEvent]);

        return $tickets;
    }


    /**
     * @Get("/performanceevents/{id}/pricecategories", requirements={"id" = "\d+"})
     * @RestView
     * @ParamConverter("performanceEvent", class="AppBundle:PerformanceEvent")
     * @QueryParam(
     *     name="locale",
     *     requirements="uk|en",
     *     default="uk",
     *     description="Selects language of data you want to receive"
     * )
     * @QueryParam(
     *     name="id",
     *     requirements="\d+",
     *     description="PerformanceEvent ID"
     * )
     */
    public function getPriceCategoriesAction(ParamFetcher $paramFetcher, PerformanceEvent $performanceEvent)
    {
        $priceCategory = $performanceEvent->getPriceCategories();

        $em = $this->getDoctrine()->getManager();

        foreach ($priceCategory as $category) {
            $category->getVenueSector()->setLocale($paramFetcher->get('locale'));
            $em->refresh($category->getVenueSector());

            if ($category->getVenueSector()->getTranslations()) {
                $category->getVenueSector()->unsetTranslations();
            }
        }
        return $priceCategory;
    }
}
