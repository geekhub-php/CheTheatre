<?php

namespace App\Model;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class Link
{
    /**
     * @param $href
     */
    public function __construct($href)
    {
        $this->href = $href;
    }

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
