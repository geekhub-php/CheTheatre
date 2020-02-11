<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Entity\Tag;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tags")
 */
class TagsController extends AbstractController
{
    /**
     * @Route("/{slug}/posts", name="get_tags_posts", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns posts by tag",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Post::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when tag by {slug} not found in database",
     * )
     * @SWG\Get(deprecated=true)
     */
    public function getPostsAction($slug)
    {
        /** @var Tag $tag */
        $tag = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Tag::class)
                    ->findOneBySlug($slug)
        ;

        if (!$tag) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        return $tag->getPosts();
    }
}
