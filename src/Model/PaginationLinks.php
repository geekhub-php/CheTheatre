<?php

namespace App\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("all")
 */
class PaginationLinks
{
    /**
     * @Expose()
     * @Type("App\Model\Link")
     */
    protected $self;

    /**
     * @Expose()
     * @Type("App\Model\Link")
     */
    protected $first;

    /**
     * @Expose()
     * @Type("App\Model\Link")
     */
    protected $prev;

    /**
     * @Expose()
     * @Type("App\Model\Link")
     */
    protected $next;

    /**
     * @Expose()
     * @Type("App\Model\Link")
     */
    protected $last;

    /**
     * @return mixed
     */
    public function getSelf()
    {
        return $this->self;
    }

    /**
     * @param $self
     * @return $this
     */
    public function setSelf($self)
    {
        $this->self = $self;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @param $first
     * @return $this
     */
    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * @param $prev
     * @return $this
     */
    public function setPrev($prev)
    {
        $this->prev = $prev;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param $next
     * @return $this
     */
    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @param $last
     * @return $this
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }
}
