<?php

namespace App\Controller\Api;

use App\Model\Link;
use App\Model\PaginationLinks;
use App\Model\PerformancesResponse;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/performances")
 */
class PerformancesController extends AbstractController
{
    /**
     * @Route("", name="get_performances", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns when all parameters were correct",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=PerformancesResponse::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when the entity is not found",
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getList(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $performances = $em
            ->getRepository('App:Performance')
            ->findBy(
                ['festival' => null],
                ['premiere' => 'DESC'],
                $paramFetcher->get('limit'),
                ($paramFetcher->get('page')-1) * $paramFetcher->get('limit')
            );

        $performancesTranslated = array();

        foreach ($performances as $performance) {
            $performance->setLocale($paramFetcher->get('locale'));
            $em->refresh($performance);

            if ($performance->getTranslations()) {
                $performance->unsetTranslations();
            }

            $performancesTranslated[] = $performance;
        }

        $performances = $performancesTranslated;

        $performancesResponse = new PerformancesResponse();
        $performancesResponse->setPerformances($performances);
        $performancesResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('App:Performance')->getCount());
        $performancesResponse->setPageCount(ceil($performancesResponse->getTotalCount() / $paramFetcher->get('limit')));
        $performancesResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl('get_performances', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $paramFetcher->get('page'),
        ], true
        );

        $first = $this->generateUrl('get_performances', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
        ], true
        );

        $nextPage = $paramFetcher->get('page') < $performancesResponse->getPageCount() ?
            $this->generateUrl('get_performances', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
            ], true
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_performances', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')-1,
            ], true
            ) :
            'false';

        $last = $this->generateUrl('get_performances', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $performancesResponse->getPageCount(),
        ], true
        );

        $links = new PaginationLinks();

        $performancesResponse->setLinks($links->setSelf(new Link($self)));
        $performancesResponse->setLinks($links->setFirst(new Link($first)));
        $performancesResponse->setLinks($links->setNext(new Link($nextPage)));
        $performancesResponse->setLinks($links->setPrev(new Link($previsiousPage)));
        $performancesResponse->setLinks($links->setLast(new Link($last)));

        foreach ($performances as $performance) {
            $performance->setLinks([
                ['rel' => 'self', 'href' => $this->generateUrl('get_performance', ['slug' => $performance->getSlug()], true)],
                ['rel' => 'self.roles', 'href' => $this->generateUrl('get_performance_roles', ['slug' => $performance->getSlug()], true)],
                ['rel' => 'self.events', 'href' => $this->generateUrl('get_performanceevents', ['performance' => $performance->getSlug()], true)],
            ]);
        }

        return $performancesResponse;
    }

    /**
     * @Route("/{slug}", name="get_performance", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns Performance by unique property {slug}",
     *     @Model(type=Performance::class)
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when Performance was not found in database",
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em
            ->getRepository('App:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $performance->setLocale($paramFetcher->get('locale'));
        $em->refresh($performance);

        if ($performance->getTranslations()) {
            $performance->unsetTranslations();
        }

        return $performance;
    }

    /**
     * @Route("/{slug}/roles", name="get_performance_roles", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns Performance roles by his unique {slug}",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Role::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when Performance by slug was not found in database",
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getRolesAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em
            ->getRepository('App:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $performance->setLocale($paramFetcher->get('locale'));
        $em->refresh($performance);

        if ($performance->getTranslations()) {
            $performance->unsetTranslations();
        }

        $roles = $performance->getRoles();
        $rolesTrans = [];

        foreach ($roles as $role) {
            $role->setLocale($paramFetcher->get('locale'));
            $em->refresh($role);

            if ($role->getTranslations()) {
                $role->unsetTranslations();
            }

            $role->getEmployee()->setLocale($paramFetcher->get('locale'));
            $em->refresh($role->getEmployee());

            if ($role->getEmployee()->getTranslations()) {
                $role->getEmployee()->unsetTranslations();
            }

            $rolesTrans[] = $role;
        }
        $roles = $rolesTrans;

        return $roles;
    }

    /**
     * @Route("/{slug}/performanceevents", name="get_performance_performance_events", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns Performance events by Performance {slug}",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=PerformanceEvent::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when Performance by {slug} was not found in database",
     * )
     * @SWG\Get(deprecated=true)
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getPerformanceEventsAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em->getRepository('App:Performance')->findOneByslug($slug);

        if (!$performance) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $performance->setLocale($paramFetcher->get('locale'));
        $em->refresh($performance);

        if ($performance->getTranslations()) {
            $performance->unsetTranslations();
        }

        $performanceEvents = $performance->getPerformanceEvents();
        $performanceEventsTrans = [];

        foreach ($performanceEvents as $performanceEvent) {
            $performanceEvent->setLocale($paramFetcher->get('locale'));
            $em->refresh($performanceEvent);
            if ($performanceEvent->getTranslations()) {
                $performanceEvent->unsetTranslations();
            }
            $performanceEventsTrans[] = $performanceEvent;
        }
        $performanceEvents = $performanceEventsTrans;

        return $performanceEvents;
    }
}
