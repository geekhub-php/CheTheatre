<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Pagerfanta;
use AppBundle\Model\PostsResponse;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * @RouteResource("Post")
 */
class PostsController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Posts",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entities with given limit and offset are not found",
     *  },
     *  output = "array<AppBundle\Model\PostsResponse>"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $queryBuilder = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Post')->findBy([], ['createdAt' => 'DESC']);

        $paginater = new Pagerfanta(new ArrayAdapter($queryBuilder));
        $paginater
            ->setMaxPerPage($paramFetcher->get('limit'))
            ->setCurrentPage($paramFetcher->get('page'))
        ;
        $postsResponse = new PostsResponse();
        $postsResponse->setPosts($paginater->getCurrentPageResults());
        $postsResponse->setPageCount($paginater->getNbPages());

        $nextPage = $paginater->hasNextPage() ?
            $this->generateUrl('get_posts', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')+1,
                )
            ) :
            'false';

        $previsiousPage = $paginater->hasPreviousPage() ?
            $this->generateUrl('get_posts', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')-1,
                )
            ) :
            'false';

        $postsResponse->setNextPage($nextPage);
        $postsResponse->setPreviousPage($previsiousPage);

        return $postsResponse;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns an Post by slug",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entity is not found",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Post slug"}
     *  },
     *  output = "AppBundle\Entity\Post"
     * )
     *
     * @RestView
     */
    public function getAction($slug)
    {
        $post = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Post')->findOneByslug($slug);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        return $post;
    }
}
