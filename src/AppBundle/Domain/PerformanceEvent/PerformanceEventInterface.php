<?php

namespace AppBundle\Domain\PerformanceEvent;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Exception\NotFoundException;

interface PerformanceEventInterface
{
    /**
     * Get PerformanceEvent by ID
     *
     * @param int $id
     *
     * @return PerformanceEvent
     * @throws NotFoundException
     */
    public function getById(int $id): PerformanceEvent;
}
