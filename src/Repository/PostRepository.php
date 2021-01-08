<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Common\Persistence\ManagerRegistry;

class PostRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

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
            ->enableResultCache(self::CACHE_TTL)
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

        $query = $qb->getQuery()->enableResultCache(self::CACHE_TTL);

        return $query->getSingleScalarResult();
    }

    /**
     * @param string $tagSlug
     * @param bool $pinned
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getTaggedPostsQuery($tagSlug = null, $pinned = false)
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.pinned = :pinned')
            ->setParameter('pinned', $pinned)
        ;

        if ($tagSlug) {
            $qb->join('u.tags', 't')->andWhere('t.slug = :slug')->setParameter('slug', $tagSlug);
        }

        return $qb;
    }
}
