<?php

namespace AppBundle\Model;

use AppBundle\Model\AbstractPaginatedModel;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class PerformancesResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class PerformancesResponse extends AbstractPaginatedModel
{
    /**
     * @var Array[]
     * @Type("array")
     * @Expose
     */
    protected $performances;

    /**
     * @var int
     *
     * @Type("integer")
     * @Expose
     */
    protected $count;

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
     * @return int
     */
    public function getCount()
    {
        return count($this->getPerformances());
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }
}
