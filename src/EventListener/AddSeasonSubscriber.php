<?php

namespace App\EventListener;

use App\Entity\PerformanceEvent;
use App\Entity\RepertoireSeason;
use App\Repository\RepertoireSeasonRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class AddSeasonSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $keyEntity => $entity) {
            if ($entity instanceof PerformanceEvent) {
                $performance = $entity->getPerformance();
                $currentSeason = $em->getRepository(RepertoireSeason::class)
                    ->findSeasonByDate($entity->getDateTime());
                $performance->addSeason($currentSeason);
                $classMetadata = $em->getClassMetadata(get_class($performance));
                $uow->computeChangeSet($classMetadata, $performance);
            }
        }
    }
}
