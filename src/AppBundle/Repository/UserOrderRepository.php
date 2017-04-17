<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;

class UserOrderRepository extends AbstractRepository
{
    /**
     * Method returns opened customer order
     *
     * @param User $param
     * @return UserOrder |null
     */
    public function findLastOpenOrder(User $param)
    {
        return $this->createQueryBuilder('o')
            ->join('o.user', 'u')
            ->where('o.status = :opened')
            ->andWhere('u.id = :param')
            ->setParameter(':param', $param->getId())
            ->setParameter(':opened', UserOrder::STATUS_OPENED)
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
