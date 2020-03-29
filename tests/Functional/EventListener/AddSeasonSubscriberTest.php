<?php

namespace App\Tests\Functional\EventListener;

use App\Entity\Performance;
use App\Entity\PerformanceEvent;
use App\Entity\RepertoireSeason;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddSeasonSubscriberTest extends WebTestCase
{
    public function testOnFlush()
    {
        static::bootKernel();
        $container = self::$container;
        $em = $container->get(EntityManagerInterface::class);
        $em->getConnection()->beginTransaction();

        $season = (new RepertoireSeason())
            ->setNumber(100500)
            ->setStartDate(new \DateTime('2199-01-01 00:00:00'))
            ->setEndDate(new \DateTime('2199-12-31 00:00:00'));
        $em->persist($season);
        $em->flush();

        /** @var Performance $performance */
        $performance = $em->getRepository(Performance::class)
            ->findOneBy([]);

        $this->assertInstanceOf(Performance::class, $performance);
        $this->assertNotContains(100500, array_map(function(RepertoireSeason $season) {
            return $season->getNumber();
        }, $performance->getSeasons()->toArray()));

        $performanceId = $performance->getId();
        $performanceEvent = (new PerformanceEvent())
            ->setPerformance($performance)
            ->setDateTime(new \DateTime('2199-06-01 00:00:00'))
            ->setVenue('moon')
            ->setCreatedBy('evil')
            ->setUpdatedBy('god');
        $em->persist($performanceEvent);
        $em->flush();

        $performance = $em->getRepository(Performance::class)
            ->find($performanceId);
        $this->assertContains(100500, array_map(function(RepertoireSeason $season) {
            return $season->getNumber();
        }, $performance->getSeasons()->toArray()));
    }
}
