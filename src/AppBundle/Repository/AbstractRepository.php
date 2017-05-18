<?php

namespace AppBundle\Repository;

use AppBundle\Exception\DuplicateException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository implements BasicRepositoryInterface
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->select($qb->expr()->count('u'))->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function save($entity, $doFlush = true)
    {
        try {
            $this->getEntityManager()->persist($entity);
            if ($doFlush) {
                $this->getEntityManager()->flush();
            }
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateException('Entity has duplicate by one of unique field');
        }
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function remove($entity, $doFlush = true)
    {
        $this->getEntityManager()->remove($entity);
        if ($doFlush) {
            $this->getEntityManager()->flush();
        }
    }
}
