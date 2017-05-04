<?php

namespace AppBundle\Repository;

class ClientRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string $ip
     *
     * @return Client|null
     */
    public function findIpBanned($ip)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.ip = :param')
            ->andWhere('c.banned = 1')
            ->setParameter(':param', $ip)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
