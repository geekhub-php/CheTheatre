<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class PerformanceEventsResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class PerformanceEventsResponse
{
    /**
     * @var Array[]
     * @Type("array")
     * @Expose
     */
    protected $performanceEvents;

    /**
     * @return mixed
     */
    public function getPerformanceEvents()
    {
        return $this->performanceEvents;
    }

    /**
     * @param mixed $performanceEvents
     * @return $this
     */
    public function setPerformanceEvents($performanceEvents)
    {
        $this->performanceEvents = $performanceEvents;

        return $this;
    }
}
