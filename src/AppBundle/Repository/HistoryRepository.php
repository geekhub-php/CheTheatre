<?php

namespace AppBundle\Repository;

class HistoryRepository extends AbstractRepository
{
    public function findAllHistory($limit, $page)
    {
        $qb = $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->orderBy('u.createdAt', 'DESC')
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getCount()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select($qb->expr()->count('u'));

        $query = $qb->getQuery();

        $query->useResultCache(true, 3600);

        return $query->getSingleScalarResult();
    }
}
