<?php

namespace App\Controller;

use App\Entity\Tag;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @RouteResource("Tag")
 */
class TagsController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns tags",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Tag::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when tag by {slug} not found in database",
     * )
     * @SWG\Get(deprecated=true)
     *
     * @RestView
     */
    public function getPostsAction($slug)
    {
        $tag = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('App:Tag')
                    ->findOneBySlug($slug)
        ;

        if (!$tag) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        return $tag->getPosts();
    }
}
