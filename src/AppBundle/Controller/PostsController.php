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
use AppBundle\Model\PostsResponse;

/**
 * @RouteResource("Post")
 * @Cache(smaxage="129600", public=true)
 */
class PostsController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Posts",
     *  statusCodes={
     *      200="Returned when all parameters were correct",
     *      404="Returned when the entities with given limit and offset are not found",
     *  },
     *  output = "array<AppBundle\Model\PostsResponse>"
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

        $posts = $em
            ->getRepository('AppBundle:Post')
            ->findBy([], ['createdAt' => 'DESC'], $paramFetcher->get('limit'), ($paramFetcher->get('page')-1) * $paramFetcher->get('limit'));

        $postsTranslated = [];

        foreach ($posts as $post) {
            $post->setLocale($paramFetcher->get('locale'));
            $em->refresh($post);

            if ($post->getTranslations()) {
                $post->unsetTranslations();
            }

            $tags = $post->getTags();

            foreach ($tags as $tag) {
                $tag->setLocale($paramFetcher->get('locale'));
                $em->refresh($tag);

                if ($tag->getTranslations()) {
                    $tag->unsetTranslations();
                }
            }

            $postsTranslated[] = $post;
        }

        $posts = $postsTranslated;

        $postsResponse = new PostsResponse();
        $postsResponse->setPosts($posts);
        $postsResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->getCount());
        $postsResponse->setPageCount(ceil($postsResponse->getTotalCount() / $paramFetcher->get('limit')));
        $postsResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl('get_posts', [
            'limit' => $paramFetcher->get('limit'),
            'page' => $paramFetcher->get('page'),
        ], true
        );

        $first = $this->generateUrl('get_posts', [], true);

        $nextPage = $paramFetcher->get('page') < $postsResponse->getPageCount() ?
            $this->generateUrl('get_posts', [
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
            ], true
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_posts', [
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')-1,
            ], true
            ) :
            'false';

        $last = $this->generateUrl('get_posts', [
            'limit' => $paramFetcher->get('limit'),
            'page' => $postsResponse->getPageCount(),
        ], true
        );

        $links = new PaginationLinks();

        $postsResponse->setLinks($links->setSelf(new Link($self)));
        $postsResponse->setLinks($links->setFirst(new Link($first)));
        $postsResponse->setLinks($links->setNext(new Link($nextPage)));
        $postsResponse->setLinks($links->setPrev(new Link($previsiousPage)));
        $postsResponse->setLinks($links->setLast(new Link($last)));

        return $postsResponse;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns an Post by unique property {slug}",
     *  statusCodes={
     *      200="Returned when Post by {slug} was found",
     *      404="Returned when Post by {slug} was not found",
     *  },
     *  output = "AppBundle\Entity\Post"
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     *
     * @RestView
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em
            ->getRepository('AppBundle:Post')->findOneByslug($slug);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $post->setLocale($paramFetcher->get('locale'));
        $em->refresh($post);

        if ($post->getTranslations()) {
            $post->unsetTranslations();
        }

        $tags = $post->getTags();

        foreach ($tags as $tag) {
            $tag->setLocale($paramFetcher->get('locale'));
            $em->refresh($tag);

            if ($tag->getTranslations()) {
                $tag->unsetTranslations();
            }
        }

        return $post;
    }
}
