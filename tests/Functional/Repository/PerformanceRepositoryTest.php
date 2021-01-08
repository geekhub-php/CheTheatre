<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Performance;
use App\Entity\RepertoireSeason;
use App\Repository\PerformanceRepository;
use App\Repository\RepertoireSeasonRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformanceRepositoryTest extends WebTestCase
{
    public function testFindAllWithinSeasonsExcept()
    {
        self::bootKernel();
        $seasons = self::$container
            ->get(RepertoireSeasonRepository::class)
            ->findAll();
        self::assertNotEmpty($seasons);

        /** @var PerformanceRepository $performanceRepository */
        $performanceRepository = self::$container->get(PerformanceRepository::class);

        foreach ($seasons as $season) {
            $performances = $performanceRepository->findAllWithinSeasonsExcept($season);
            static::assertNotEmpty($performances);

            foreach ($performances as $performance) {
                $seasonIds = array_map(
                    fn (RepertoireSeason $season) => $season->getId(),
                    $performance->getSeasons()->toArray()
                );
                self::assertNotContains(
                    $season->getId(),
                    $seasonIds,
                    sprintf('Expect that performance %s doesn\'t contain %s season', $performance->getId(), $season->getId())
                );
            }
        }
    }

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
