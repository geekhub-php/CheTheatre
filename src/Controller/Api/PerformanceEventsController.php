<?php

namespace App\Controller\Api;

use App\Model\PerformanceEventsResponse;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/performanceevents")
 */
class PerformanceEventsController extends AbstractController
{
    const MAX_DAYS_PER_GET = 367;

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="get_performanceevents", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a collection of theatre performanceEvents",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=PerformanceEventsResponse::class))
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returns when date diff more than 1 year",
     * )
     *
     * @QueryParam(name="fromDate", default="today", requirements="\d{2}-\d{2}-\d{4}|today" , description="Find entries from this date, fromat=dd-mm-yyyy")
     * @QueryParam(name="toDate", default="+1 Year", requirements="\d{2}-\d{2}-\d{4}|\+1 Year" , description="Find entries to this date, fromat=dd-mm-yyyy")
     * @QueryParam(name="limit", default="all", requirements="\d+|all" , description="Count of entities in collection")
     * @QueryParam(name="performance", description="Performance slug")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $dateDiff = strtotime($paramFetcher->get('toDate')) - strtotime($paramFetcher->get('fromDate'));

        if (self::MAX_DAYS_PER_GET < abs(floor($dateDiff/(60*60*24)))) {
            throw new BadRequestHttpException(sprintf('You can\'t get more than "%s" days', self::MAX_DAYS_PER_GET));
        }

        $limit = 'all' == $paramFetcher->get('limit')
            ? null
            : (int) $paramFetcher->get('limit');

        $performanceEvents = $em->getRepository('App:PerformanceEvent')
            ->findByDateRangeAndSlug(
                new \DateTime($paramFetcher->get('fromDate')),
                new \DateTime($paramFetcher->get('toDate')),
                $paramFetcher->get('performance'),
                $limit
            )
        ;

        $performanceEventsResponse = new PerformanceEventsResponse();
        $performanceEventsResponse->setPerformanceEvents($performanceEvents);

        return new Response(
            $this->serializer->serialize(
                $performanceEventsResponse,
                'json',
                SerializationContext::create()->setGroups(array('poster')))
        );
    }

    /**
     * @Route("/{id}", name="get_performance_event", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns one PerformanceEvent by Id",
     *     @Model(type=PerformanceEvent::class)
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when PerformanceEvent by id was not found id database",
     * )
     * @SWG\Get(deprecated=true)
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getAction(ParamFetcher $paramFetcher, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $performanceEvent = $em->getRepository('App:PerformanceEvent')->findOneById($id);

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
