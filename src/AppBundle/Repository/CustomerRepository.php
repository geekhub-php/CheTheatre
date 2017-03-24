<?php

namespace AppBundle\Repository;

class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param  string $param
     * @return object
     */
    public function findOneByApiKey($param)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.apiKey = :param')
            ->setParameter(':param', $param)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
