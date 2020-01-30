<?php

namespace App\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("all")
 */
trait LinksTrait
{
    /**
     * @var Array[]
     *
     * @Type("array")
     * @Expose
     */
    protected $links;

    /**
     * @return \Array[]
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
