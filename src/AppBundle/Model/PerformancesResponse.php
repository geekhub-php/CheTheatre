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
class PerformancesResponse extends AbstractPaginatedModel
{
    /**
     * @var Array[]
     * @Type("array")
     * @Expose
     */
    protected $performances;

    /**
     * @return mixed
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    /**
     * @param mixed $performances
     * @return $this
     */
    public function setPerformances($performances)
    {
        $this->performances = $performances;

        return $this;
    }
}
