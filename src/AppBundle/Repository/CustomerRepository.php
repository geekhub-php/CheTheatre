<?php

namespace AppBundle\Repository;

use phpDocumentor\Reflection\Types\Object_;

class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param  string $param
     * @return Object
     */

    public function findUsernameByApiKey($param)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.apiKey = :param')
            ->setParameter(':param', $param)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
