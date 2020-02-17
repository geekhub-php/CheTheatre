<?php

namespace App\Repository;

use App\Entity\RepertoireSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method RepertoireSeason|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepertoireSeason|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepertoireSeason[]    findAll()
 * @method RepertoireSeason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepertoireSeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepertoireSeason::class);
    }

    /**
     * @return array|RepertoireSeason[]
     */
    public function findAllNotEmpty(): array
    {
        $result = $this->createQueryBuilder('r')
            ->addSelect('COUNT(rp.id) as performanceCount')
            ->leftJoin('r.performances', 'rp')
            ->addGroupBy('r.id')
            ->having('performanceCount > 0')
            ->orderBy('r.number', 'DESC')
            ->getQuery()
            ->execute()
        ;

        if (!$result || false === is_array($result)) return [];

        // todo: Use ResultSetMapping instead
        return array_map(function(array $el) {
            $el[0]->performanceCount = $el['performanceCount'];
            return $el[0];
        }, $result);
    }

    public function findCurrentSeason(): ?RepertoireSeason
    {
        $all = $this->findAllNotEmpty();
        if (empty($all)) return null;
        return array_shift($all);
    }

    public function findOneByNumber($number)
    {
        if ('current' === $number) return $this->findCurrentSeason();

        return $this->findOneBy(['number' => $number]);
    }

    // /**
    //  * @return RepertoireSeason[] Returns an array of RepertoireSeason objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RepertoireSeason
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
