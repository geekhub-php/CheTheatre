<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public const CACHE_TTL = 60*60*24;

    public function getCount()
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->select($qb->expr()->count('u'))->getQuery();

        return $query->getSingleScalarResult();
    }

    public function search(array $fields, string $query): array
    {
        $qb = $this->createQueryBuilder('e');

        foreach ($fields as $field) {
            $qb->orWhere("e.$field LIKE :query")
                ->setParameter('query', "%$query%");
        }


        return $qb->getQuery()->getResult();
    }
}
