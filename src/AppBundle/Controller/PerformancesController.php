<?php

namespace AppBundle\Controller;

use AppBundle\Model\Link;
use AppBundle\Model\PaginationLinks;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Model\PerformancesResponse;

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
     *      200="Returned when all parameters were correct",
     *      404="Returned when the entity is not found",
     *  },
     *  output = "array<AppBundle\Model\PerformancesResponse>"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $performances = $em
                        ->getRepository('AppBundle:Performance')
                        ->findBy(['festival' => null], null, $paramFetcher->get('limit'), ($paramFetcher->get('page')-1) * $paramFetcher->get('limit'));

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
        $performancesResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:Performance')->getCount());
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
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance by unique property {slug}",
     *  statusCodes={
     *      200="Returned when Performance was found in database",
     *      404="Returned when Performance was not found in database",
     *  },
     *  output = "AppBundle\Entity\Performance"
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em
            ->getRepository('AppBundle:Performance')->findOneByslug($slug);

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
     * @ApiDoc(
     * resource=true,
     *  description="Returns Performance roles by his unique {slug}",
     *  statusCodes={
     *      200="Returned when Performance by slug was found in database",
     *      404="Returned when Performance by slug was not found in database",
     *  },
     *  output = "array<AppBundle\Entity\Role>"
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function getRolesAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em
            ->getRepository('AppBundle:Performance')->findOneByslug($slug);

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
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function getPerformanceeventsAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $performance = $em->getRepository('AppBundle:Performance')->findOneByslug($slug);

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
