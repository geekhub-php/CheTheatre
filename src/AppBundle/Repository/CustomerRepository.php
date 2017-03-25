<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;

class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param  string $param
     * @return Customer
     */
    public function findOneByApiKeyToken($param)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.apiKeyToken = :param')
            ->setParameter(':param', $param)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
