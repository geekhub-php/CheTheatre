<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class PostsResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class PostsResponse
{
    /**
     * @var Array[]
     * @Type("array<AppBundle\Entity\Post>")
     * @Expose
     */
    protected $posts;

    /**
     * @var int
     *
     * @Type("integer")
     * @Accessor(getter="getCount")
     * @Expose
     */
    protected $count;

    /**
     * @var int
     *
     * @Type("integer")
     * @Expose
     */
    protected $totalCount;

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

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->getPosts());
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @param $totalCount
     * @return PostsResponse
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;

        return $this;
    }
}
