<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

trait LinksTrait
{
    /**
     * @var Link[]
     * @Type("array<AppBundle\Model\Link>")
     * @Expose
     */
    protected $links;

    /**
     * @return Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function addLink(Link $link)
    {
        $this->links = $link;
    }

    /**
     * @param  Link[] $links
     * @return $this
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }
}
