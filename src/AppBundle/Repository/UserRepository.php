<?php

namespace AppBundle\Repository;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string $apiKey
     */
    public function removeUserIntoSoftDeleTeable($apiKey)
    {
        $this->createQueryBuilder('u')
            ->delete()
            ->where('u = :param')
            ->setParameter(':param', $apiKey)
            ->getQuery()
            ->execute();
    }
}
