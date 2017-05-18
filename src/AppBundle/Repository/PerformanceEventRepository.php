<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Exception\NotFoundException;

class PerformanceEventRepository extends AbstractRepository
{
    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @param string $limit
     * @param null $performanceSlug
     * @return mixed
     */
    public function findByDateRangeAndSlug(
        \DateTime $fromDate,
        \DateTime $toDate,
        $limit = null,
        $performanceSlug = null
    ) {
        $qb = $this->createQueryBuilder('u')
            ->where('u.dateTime BETWEEN :from AND :to')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->orderBy('u.dateTime', 'ASC')
        ;

        if ($limit) {
            $qb->setMaxResults($limit);
        }

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
