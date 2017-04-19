<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\VenueSector;
use AppBundle\Exception\NotFoundException;

class PriceCategoryRepository extends AbstractRepository
{
    /**
     * Get PriceCategory for specific VenueSector
     *
     * @param PerformanceEvent $performanceEvent
     * @param VenueSector $venueSector
     *
     * @return PriceCategory[]
     * @throws NotFoundException
     */
    public function getByPerformanceEventAndVenueSector(
        PerformanceEvent $performanceEvent,
        VenueSector $venueSector
    ): array {
        $priceCategory = $this->findBy([
            'performanceEvent' => $performanceEvent,
            'venueSector' => $venueSector
        ]);

        if (empty($priceCategory)) {
            throw new NotFoundException(
                sprintf(
                    'No PriceCategory found for PerformanceEvent %s and %s',
                    $performanceEvent,
                    $venueSector
                )
            );
        }

        return $priceCategory;
    }
}
