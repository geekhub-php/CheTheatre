<?php

namespace App\Controller\Api;

use App\Entity\History;
use App\Model\HistoryResponse;
use App\Model\Link;
use App\Model\PaginationLinks;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/histories")
 */
class HistoryController extends AbstractController
{
    /**
     * @Route("", name="get_histories", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a collection of History",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=HistoryResponse::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when the entities with given limit and offset are not found",
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $histories = $em->getRepository('App:History')
            ->findAllHistory(
                $paramFetcher->get('limit'),
                ($paramFetcher->get('page')-1) * $paramFetcher->get('limit')
            )
        ;

        $historyTranslated = [];

        /** @var History $history */
        foreach ($histories as $history) {
            $history->setLocale($paramFetcher->get('locale'));
            $em->refresh($history);

            $history->unsetTranslations();

            $historyTranslated[] = $history;
        }

        $histories = $historyTranslated;

        $historyResponse = new HistoryResponse();
        $historyResponse->setHistory($histories);
        $historyResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('App:History')->getCount());
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
     * @Route("/{slug}", name="get_history", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns an History by unique property {slug}",
     *     @Model(type=History::class)
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when History by {slug} was not found",
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $history = $em
            ->getRepository('App:History')->findOneByslug($slug);

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
