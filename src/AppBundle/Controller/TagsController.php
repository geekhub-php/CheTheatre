<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @RouteResource("Tag")
 */
class TagsController extends Controller
{
    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns posts by slug tag",
     *  statusCodes={
     *      200="Returned when tag by {slug} found in database" ,
     *      404="Returned when tag by {slug} not found in database",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Unique name for every tags"}
     *  },
     *  output = "array<AppBundle\Entity\Tag>",
     * deprecated = true
     * )
     *
     * @RestView
     */
    public function getPostsAction($slug)
    {
        return new JsonResponse();
    }
}
