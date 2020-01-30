<?php

namespace App\Model;

use App\Entity\Performance;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class PerformanceEventsResponse
 * @package App\Model
 * @ExclusionPolicy("all")
 */
class PerformanceEventsResponse
{
    /**
     * @var Performance[]
     *
     * @Type("array<App\Entity\PerformanceEvent>")
     * @Expose
     */
    protected $performanceEvents;

    /**
     * @var int
     *
     * @Type("integer")
     * @Accessor(getter="getCount")
     * @Expose
     */
    protected $count;

    /**
     * @return Performance[]
     */
    public function getPerformanceEvents()
    {
        return $this->performanceEvents;
    }

    /**
     * @param  mixed $performanceEvents
     * @return $this
     */
    public function setPerformanceEvents($performanceEvents)
    {
        $this->performanceEvents = $performanceEvents;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->getPerformanceEvents());
    }
}
