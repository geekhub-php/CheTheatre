<?php

namespace App\Repository;

use App\Entity\EmployeeGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmployeeGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeGroup[]    findAll()
 * @method EmployeeGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeGroup::class);
    }

    // /**
    //  * @return EmployeeGroup[] Returns an array of EmployeeGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmployeeGroup
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
