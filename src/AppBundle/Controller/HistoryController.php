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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use AppBundle\Model\HistoryResponse;

/**
 * @RouteResource("History")
 * @Cache(smaxage="129600", public=true)
 */
class HistoryController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of History",
     *  statusCodes={
     *      200="Returned when all parameters were correct",
     *      404="Returned when the entities with given limit and offset are not found",
     *  },
     *  output = "array<AppBundle\Model\HistoryResponse>"
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

        $history = $em->getRepository('AppBundle:History')
            ->findAllHistory(
                $paramFetcher->get('limit'),
                ($paramFetcher->get('page')-1) * $paramFetcher->get('limit')
            )
        ;

        $historyTranslated = [];

        foreach ($history as $hist) {
            $hist->setLocale($paramFetcher->get('locale'));
            $em->refresh($hist);

            if ($hist->getTranslations()) {
                $hist->unsetTranslations();
            }

            $historyTranslated[] = $hist;
        }

        $history = $historyTranslated;

        $historyResponse = new HistoryResponse();
        $historyResponse->setHistory($history);
        $historyResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:History')->getCount());
        $historyResponse->setPageCount(ceil($historyResponse->getTotalCount() / $paramFetcher->get('limit')));
        $historyResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl('get_histories', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $paramFetcher->get('page'),
        ], true
        );

        $first = $this->generateUrl('get_histories', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
        ], true
        );

        $nextPage = $paramFetcher->get('page') < $historyResponse->getPageCount() ?
            $this->generateUrl('get_histories', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
            ], true
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_histories', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')-1,
            ], true
            ) :
            'false';

        $last = $this->generateUrl('get_histories', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $historyResponse->getPageCount(),
        ], true
        );

        $links = new PaginationLinks();

        $historyResponse->setLinks($links->setSelf(new Link($self)));
        $historyResponse->setLinks($links->setFirst(new Link($first)));
        $historyResponse->setLinks($links->setNext(new Link($nextPage)));
        $historyResponse->setLinks($links->setPrev(new Link($previsiousPage)));
        $historyResponse->setLinks($links->setLast(new Link($last)));

        return $historyResponse;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns an History by unique property {slug}",
     *  statusCodes={
     *      200="Returned when History by {slug} was found",
     *      404="Returned when History by {slug} was not found",
     *  },
     *  output = "AppBundle\Entity\History"
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $history = $em
            ->getRepository('AppBundle:History')->findOneByslug($slug);

        if (!$history) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $history->setLocale($paramFetcher->get('locale'));
        $em->refresh($history);

        if ($history->getTranslations()) {
            $history->unsetTranslations();
        }

        return $history;
    }
}
