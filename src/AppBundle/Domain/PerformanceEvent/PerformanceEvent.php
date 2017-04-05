<?php

namespace AppBundle\Domain\PerformanceEvent;

use AppBundle\Exception\NotFoundException;
use AppBundle\Repository\PerformanceEventRepository;
use AppBundle\Entity\PerformanceEvent as PerformanceEventEntity;

class PerformanceEvent implements PerformanceEventInterface
{
    /** @var PerformanceEventRepository */
    private $performanceEventRepository;

    /**
     * Domain PerformanceEvent constructor.
     *
     * @param PerformanceEventRepository $performanceEventRepository
     */
    public function __construct(
        PerformanceEventRepository $performanceEventRepository
    ) {
        $this->performanceEventRepository = $performanceEventRepository;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): PerformanceEventEntity
    {
        /** @var PerformanceEventEntity $performanceEventEntity */
        $performanceEventEntity = $this->performanceEventRepository->find($id);
        if (!$performanceEventEntity) {
            throw new NotFoundException('Performance Event not found by ID: '.$id);
        }

        return $performanceEventEntity;
    }
}
