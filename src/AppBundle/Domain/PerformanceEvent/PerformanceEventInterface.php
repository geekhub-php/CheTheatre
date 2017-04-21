<?php

namespace AppBundle\Domain\PerformanceEvent;

use AppBundle\Entity\PerformanceEvent as PerformanceEventEntity;
use AppBundle\Exception\NotFoundException;

interface PerformanceEventInterface
{
    /**
     * Get PerformanceEvent by ID
     *
     * @param int $id
     *
     * @return PerformanceEventEntity
     * @throws NotFoundException
     */
    public function getById(int $id): PerformanceEventEntity;
}