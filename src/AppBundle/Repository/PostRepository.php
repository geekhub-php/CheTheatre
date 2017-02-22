<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;

class PostRepository extends AbstractRepository
{
    /**
     * @param int $limit
     * @param int $page
     * @param string $tagSlug
     * @return Post[]
     */
    public function findAllOrByTag($limit, $page, $tagSlug = null)
    {
        $taggedPosts = $this->getTaggedPostsQuery($tagSlug)
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->execute()
        ;

        $pinnedPosts = $this->getTaggedPostsQuery($tagSlug, true)->getQuery()->execute();

        return array_merge($pinnedPosts, $taggedPosts);
    }

    /**
     * @param string $tagSlug
     * @return int
     */
    public function getCount($tagSlug = null)
    {
        $qb = $this->getTaggedPostsQuery($tagSlug);
        $qb->select($qb->expr()->count('u'));

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * @param string $tagSlug
     * @param bool $pinned
     * @return \Doctrine\ORM\QueryBuilder
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function getTaggedPostsQuery($tagSlug = null, $pinned = false)
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.pinned = :pinned')
            ->setParameter('pinned', $pinned)
        ;

        if ($tagSlug !==null) {
            $qb->join('u.tags', 't')->andWhere('t.slug = :slug')->setParameter('slug', $tagSlug);
        }

        return $qb;
    }
}
