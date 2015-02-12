<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class PerformancesResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class PerformancesResponse
{
    /**
     * @var Array[]
     * @Type("array")
     * @Expose
     */
    protected $performances;

    /**
     * @var string
     * @Type("string")
     * @Expose
     */
    protected $nextPage;

    /**
     * @var string
     * @Type("string")
     * @Expose
     */
    protected $previousPage;

    /**
     * @var integer
     * @Type("integer")
     * @Expose
     */
    protected $pageCount;

    /**
     * @return mixed
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    /**
     * @param mixed $performances
     */
    public function setPerformances($performances)
    {
        $this->performances = $performances;
    }

    /**
     * @return mixed
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * @param mixed $nextPage
     */
    public function setNextPage($nextPage)
    {
        $this->nextPage = $nextPage;
    }

    /**
     * @return mixed
     */
    public function getPreviousPage()
    {
        return $this->previousPage;
    }

    /**
     * @param mixed $previousPage
     */
    public function setPreviousPage($previousPage)
    {
        $this->previousPage = $previousPage;
    }

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
}
