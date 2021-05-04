<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleDoctrineSubscriber implements EventSubscriber
{
    private const LOCALE_DEFAULT = 'uk';
    private const LOCALE_AVAILABLE = ['uk', 'en'];

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!method_exists($entity, 'setLocale')) {
            return;
        }

        $request = $this->requestStack->getMasterRequest();
        $locale = $request
            ? $request->query->get('locale', self::LOCALE_DEFAULT)
            : self::LOCALE_DEFAULT;

        if (!in_array($locale, self::LOCALE_AVAILABLE)) {
            return;
        }

        $entity->setLocale($locale);
    }
}
