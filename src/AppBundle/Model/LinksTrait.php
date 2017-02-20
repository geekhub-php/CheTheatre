<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("all")
 */
trait LinksTrait
{
    /**
     * @var link[]
     *
     * @Type("array")
     * @Expose
     */
    protected $links;

    /**
     * @var link[]
     * @return link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param  mixed $links
     * @return $this
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }
}
