<?php

namespace AppBundle\Repository;

class SwindlerRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSwindlerIsBanned($ip)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.ip = :param')
            ->andWhere('s.banned = 1')
            ->setParameter(':param', $ip)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
