<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class PostsResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class PostsResponse extends AbstractPaginatedModel
{
    /**
     * @var Array[]
     * @Type("array<AppBundle\Entity\Post>")
     * @Expose
     */
    protected $posts;

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }
}
