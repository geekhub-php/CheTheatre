<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerOrder;

class CustomerOrderRepository extends AbstractRepository
{
    /**
     * Method returns opened customer order
     *
     * @param Customer $param
     * @return CustomerOrder|null
     */
    public function findLastOpenOrder(Customer $param)
    {
        return $this->createQueryBuilder('o')
            ->join('o.customer', 'c')
            ->where('o.status = :opened')
            ->andWhere('c.id = :param')
            ->setParameter(':param', $param->getId())
            ->setParameter(':opened', CustomerOrder::STATUS_OPENED)
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
