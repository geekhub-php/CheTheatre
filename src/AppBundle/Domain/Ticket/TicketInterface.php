<?php

namespace AppBundle\Domain\Ticket;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Ticket as TicketEntity;
use AppBundle\Exception\NotFoundException;

interface TicketInterface
{
    /**
     * @param PerformanceEvent $performanceEvent
     * @param bool $force
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @return int
     */
    public function generateSet(PerformanceEvent $performanceEvent, bool $force = false);

    /**
     * Get ticket by ID
     *
     * @param string $id
     *
     * @return TicketEntity
     * @throws NotFoundException
     */
    public function getById(string $id): TicketEntity;
}
