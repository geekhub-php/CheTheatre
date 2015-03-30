<?php

namespace AppBundle\Repository;

class PostRepository extends AbstractRepository
{
    public function findAllOrByTag($limit, $page, $tagSlug = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->orderBy('u.createdAt', 'DESC')
        ;

        if ($tagSlug) {
            $qb->join('u.tags', 't')->andWhere('t.slug = :slug')->setParameter('slug', $tagSlug);
        }

        $query = $qb->getQuery();

        $query->useResultCache(true, 3600);

        return $query->execute();
    }

    public function getCount($tagSlug = null)
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->select($qb->expr()->count('u'));

        if ($tagSlug) {
            $qb->join('u.tags', 't')->andWhere('t.slug = :slug')->setParameter('slug', $tagSlug);
        }

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }
}
