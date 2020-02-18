<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Performance;
use App\Repository\PerformanceRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformanceRepositoryTest extends WebTestCase
{
    public function testFindAllWithinSeasons()
    {
        self::bootKernel();
        $performances = self::$container
            ->get(PerformanceRepository::class)
            ->findAllWithinSeasons();

        $this->assertNotEmpty($performances);
        $this->allPerformancesHasAtLeastOneSeason($performances);
    }

    private function allPerformancesHasAtLeastOneSeason(array $performances)
    {
        $this->assertNotEmpty(array_filter($performances, function (Performance $performance) {
            return !$performance->getSeasons()->isEmpty();
        }));
        $this->assertEmpty(array_filter($performances, function (Performance $performance) {
            return $performance->getSeasons()->isEmpty();
        }));
    }
}
