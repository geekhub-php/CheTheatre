<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Performance;
use App\Entity\PerformanceEvent;
use App\Repository\PerformanceEventRepository;
use Doctrine\ORM\Cache\Logging\CacheLogger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformanceEventRepositoryTest extends WebTestCase
{
    public function testDoctrineCache()
    {
        self::markTestSkipped('Cache not ready');
        self::bootKernel();
        $cacheLogger = $this->createMock(CacheLogger::class);
        $cacheLogger->expects($this->once())->method('queryCacheHit');

        $em = self::$container->get('doctrine.orm.entity_manager');
        $em->getConfiguration()
            ->getSecondLevelCacheConfiguration()
            ->setCacheLogger($cacheLogger);

        /** @var Performance $performance */
        $performance = $em->getRepository(Performance::class)->findOneBy([]);
        $repository = $em->getRepository(PerformanceEvent::class);

        $noCacheHit = $this->getEvents($repository, $performance);
        $this->assertNotEmpty($noCacheHit);
        $firstCacheHit = $this->getEvents($repository, $performance);
        $this->assertEquals($noCacheHit, $firstCacheHit);

        $event = (new PerformanceEvent())
            ->setDateTime(new \DateTime())
            ->setPerformance($performance)
            ->setVenue('here')
            ->setCreatedBy('evil')
            ->setUpdatedBy('god');
        $em->persist($event);
        $em->flush();

        $noHitAfterUpdate = $this->getEvents($repository, $performance);
    }

    /**
     * @return PerformanceEvent[]
     */
    private function getEvents(PerformanceEventRepository $repository, Performance $performance)
    {
        return $repository->findByDateRangeAndSlug(
            new \DateTime('-10 Year'),
            new \DateTime('+10 Year'),
            $performance->getSlug()
        );
    }
}
