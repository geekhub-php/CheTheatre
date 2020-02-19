<?php

namespace App\Repository;

use App\Entity\RepertoireSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RepertoireSeason|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepertoireSeason|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepertoireSeason[]    findAll()
 * @method RepertoireSeason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepertoireSeasonRepository extends ServiceEntityRepository
{
    const MULTI_SEASON = -1;
    const CURRENT_SEASON = 0;
    private $performanceRepository;

    public function __construct(ManagerRegistry $registry, PerformanceRepository $performanceRepository)
    {
        parent::__construct($registry, RepertoireSeason::class);
        $this->performanceRepository = $performanceRepository;
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

    public function findSeasonByDate(\DateTime $dateTime): ?RepertoireSeason
    {
        $season = $this->createQueryBuilder('rs')
            ->where('rs.startDate < :startDate')
            ->andWhere('rs.endDate > :endDate')
            ->setParameter('startDate', $dateTime)
            ->setParameter('endDate', $dateTime)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        if ($season) return $season;

        $season = $this->createQueryBuilder('rs')
            ->where('rs.startDate > :startDate')
            ->setParameter('startDate', $dateTime)
            ->orderBy('rs.startDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        return $season;
    }

    public function findCurrentSeason(): ?RepertoireSeason
    {
        $season = $this->findSeasonByDate(new \DateTime());
        if ($season) return $season;

        $all = $this->findAllNotEmpty();
        if (empty($all)) return null;
        return array_shift($all);
    }

    public function getMultiSeason(): RepertoireSeason
    {
        $multiSeason = new RepertoireSeason();
        $multiSeason->setNumber(self::MULTI_SEASON);
        $multiSeason->setPerformances($this->performanceRepository->findAllWithinSeasons());

        return $multiSeason;
    }

    public function findOneByNumber($number): ?RepertoireSeason
    {
        if (self::CURRENT_SEASON == $number) return $this->findCurrentSeason();
        if (self::MULTI_SEASON == $number) return $this->getMultiSeason();

        return $this->findOneBy(['number' => $number]);
    }
}
