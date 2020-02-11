<?php

namespace App\Repository;

use App\Entity\Performance;
use Doctrine\Common\Persistence\ManagerRegistry;

class PerformanceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Performance::class);
    }
}
