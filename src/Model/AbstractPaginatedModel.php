<?php

namespace App\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class AbstractPaginatedModel
 * @ExclusionPolicy("all")
 */
abstract class AbstractPaginatedModel
{
    /**
     * @var string
     * @Type("App\Model\PaginationLinks")
     * @Expose
     */
    protected $_links;

    /**
     * @var integer
     * @Type("integer")
     */
    protected $pageCount;

    /**
     * @var int
     *
     * @Type("integer")
     * @Expose
     */
    protected $page;

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
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * @param mixed $pageCount
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
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
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->_links;
    }

    /**
     * @param  mixed $_links
     * @return $this
     */
    public function setLinks($_links)
    {
        $this->_links = $_links;

        return $this;
    }
}
