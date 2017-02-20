<?php

namespace AppBundle\Model;

use AppBundle\Entity\Performance;
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
     * @var Performance[]
     * @Type("array")
     * @Expose
     */
    protected $performances;

    /**
     * @var int
     *
     * @Type("integer")
     * @Accessor(getter="getCount")
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
     * @param  mixed $performances
     * @return $this
     */
    public function setPerformances($performances)
    {
        $this->performances = $performances;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->getPerformances());
    }
}
