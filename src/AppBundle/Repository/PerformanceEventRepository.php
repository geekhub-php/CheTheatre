<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Exception\NotFoundException;

class PerformanceEventRepository extends AbstractRepository
{
    public function findByDateRangeAndSlug(\DateTime $fromDate, \DateTime $toDate, $performanceSlug = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->WHERE('u.dateTime BETWEEN :from AND :to')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->orderBy('u.dateTime', 'ASC')
        ;

        if ($performanceSlug) {
            $qb->join('u.performance', 'p')->andWhere('p.slug = :slug')->setParameter('slug', $performanceSlug);
        }

        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * Get PerformanceEvent by ID
     *
     * @param int $id
     *
     * @return PerformanceEvent
     * @throws NotFoundException
     */
    public function getById(int $id): PerformanceEvent
    {
        /** @var PerformanceEvent $performanceEvent */
        $performanceEvent = $this->find($id);
        if (!$performanceEvent) {
            throw new NotFoundException('Performance Event not found by ID: '.$id);
        }

        return $performanceEvent;
    }
}
