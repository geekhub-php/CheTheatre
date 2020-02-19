<?php

namespace App\Tests\Functional\Repository;

use App\Entity\RepertoireSeason;
use App\Repository\RepertoireSeasonRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepertoireSeasonRepositoryTest extends WebTestCase
{
    public function testFindAllNotEmpty()
    {
        self::bootKernel();
        $seasons = self::$container
            ->get(RepertoireSeasonRepository::class)
            ->findAllNotEmpty();

        $this->assertIsArray($seasons);
        $this->assertNotEmpty($seasons);
        $this->assertInstanceOf(RepertoireSeason::class, $seasons[0]);
    }

    public function testFindCurrentSeason()
    {
        self::bootKernel();
        $season = self::$container
            ->get(RepertoireSeasonRepository::class)
            ->findCurrentSeason();

        $this->assertNotNull($season);
        $this->assertInstanceOf(RepertoireSeason::class, $season);
    }
}
