<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Model\Link;
use App\Model\PaginationLinks;
use App\Model\PostsResponse;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/posts")
 */
class PostsController extends AbstractController
{
    /**
     * @Route("", name="get_posts", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a collection of Posts",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=PostsResponse::class))
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
     * @QueryParam(name="tag", description="You can receive posts by Tag slug, without Tag you will receive all posts")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('App:Post')
            ->findAllOrByTag(
                $paramFetcher->get('limit'),
                ($paramFetcher->get('page')-1) * $paramFetcher->get('limit'),
                $paramFetcher->get('tag')
            )
        ;

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
        $postsResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('App:Post')->getCount($paramFetcher->get('tag')));
        $postsResponse->setPageCount(ceil($postsResponse->getTotalCount() / $paramFetcher->get('limit')));
        $postsResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl('get_posts', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $paramFetcher->get('page'),
            'tag' => $paramFetcher->get('tag'),
        ], true
        );

        $first = $this->generateUrl('get_posts', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'tag' => $paramFetcher->get('tag'),
        ], true
        );

        $nextPage = $paramFetcher->get('page') < $postsResponse->getPageCount() ?
            $this->generateUrl('get_posts', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
                'tag' => $paramFetcher->get('tag'),
            ], true
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_posts', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')-1,
                'tag' => $paramFetcher->get('tag'),
            ], true
            ) :
            'false';

        $last = $this->generateUrl('get_posts', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $postsResponse->getPageCount(),
            'tag' => $paramFetcher->get('tag'),
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
     * @Route("/{slug}", name="get_post", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns an Post by unique property {slug}",
     *     @Model(type=Post::class)
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when Post by {slug} was not found",
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Post $post */
        $post = $em->getRepository('App:Post')->findOneByslug($slug);

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
