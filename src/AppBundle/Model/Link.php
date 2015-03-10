<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class Link
{
    /**
     * @param $rel
     * @param $href
     */
    public function __construct($rel, $href)
    {
        $this->rel = $rel;
        $this->href = $href;
    }

    /**
     * @var string
     *
     * @Expose()
     * @Type("string")
     */
    protected $rel;

    /**
     * @var string
     *
     * @Expose()
     * @Type("string")
     */
    protected $href;

    /**
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @param  string $rel
     * @return $this
     */
    public function setRel($rel)
    {
        $this->rel = $rel;

        return $this;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param  string $href
     * @return $this
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }
}
