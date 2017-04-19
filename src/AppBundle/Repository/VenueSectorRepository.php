<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Venue;
use AppBundle\Entity\VenueSector;
use AppBundle\Exception\NotFoundException;

class VenueSectorRepository extends AbstractRepository
{
    /**
     * Get VenueSectors for specific Venue
     *
     * @param Venue $venue
     *
     * @return VenueSector[]
     * @throws NotFoundException
     */
    public function getByVenue(Venue $venue): array
    {
        $venueSectors = $this->findBy(['venue' => $venue]);

        if (empty($venueSectors)) {
            throw new NotFoundException('No Venue Sector found for VenueId: '.$venue->getId());
        }

        return $venueSectors;
    }
}
