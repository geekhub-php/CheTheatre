<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Model\PostsResponse;

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
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset from which to start listing")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $posts = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Post')
            ->findBy([], ['createdAt' => 'DESC'], $paramFetcher->get('limit'), $paramFetcher->get('offset'));

        $postsResponse = new PostsResponse();
        $postsResponse->setPosts($posts);
        $postsResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->getCount());

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
