<?php

namespace AppBundle\Repository;

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
        $limit = 'all',
        $performanceSlug = null
    ) {
        $qb = $this->createQueryBuilder('u')
            ->WHERE('u.dateTime BETWEEN :from AND :to')
            ->setParameter('from', $fromDate->format('Y-m-d H:i'))
            ->setParameter('to', $toDate->format('Y-m-d H:i'))
            ->orderBy('u.dateTime', 'ASC')
        ;

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        if ($performanceSlug) {
            $qb->join('u.performance', 'p')->andWhere('p.slug = :slug')->setParameter('slug', $performanceSlug);
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
