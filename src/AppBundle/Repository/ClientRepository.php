<?php

namespace AppBundle\Repository;

class ClientRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string $ip
     *
     * @return bool
     */
    public function isBanned($ip) : bool
    {
        return null !== $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.ip = :param')
            ->andWhere('c.banned = 1')
            ->setParameter(':param', $ip)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
