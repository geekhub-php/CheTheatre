<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Performance;
use AppBundle\Model\Link;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sonata\MediaBundle\Controller\Api\MediaController;
use Symfony\Component\Routing\Router;

class SerializerSubscriber implements EventSubscriberInterface
{
    /** @var MediaController  */
    protected $mediaController;

    /** @var Router */
    protected $router;

    public function __construct(MediaController $mediaController, Router $router)
    {
        $this->mediaController = $mediaController;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\Employee', 'method' => 'onPreEmployeeSerialize'],
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\PerformanceEvent', 'method' => 'onPrePerformanceEventSerialize'],
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\Performance', 'method' => 'onPrePerformanceSerialize'],
        ];
    }

    public function onPreEmployeeSerialize(ObjectEvent $event)
    {
        if (!$avatar = $event->getObject()->getAvatar()) {
            return;
        }

        $avatarLinks = $this->mediaController->getMediumFormatsAction($avatar->getId());
        $event->getObject()->avatarThumbnails = $avatarLinks;
    }

    public function onPrePerformanceEventSerialize(ObjectEvent $event)
    {
    }

    public function onPrePerformanceSerialize(ObjectEvent $event)
    {
        /** @var Performance $performance */
        $performance = $avatar = $event->getObject();

        $performance->setLinks([
            ['self' => $this->router->generate('get_performance', ['slug' => $performance->getSlug()], true)],
            ['self.roles' => $this->router->generate('get_performance_roles', ['slug' => $performance->getSlug()], true)],
            ['self.events' => $this->router->generate('get_performanceevents', ['performance' => $performance->getSlug()], true)],
        ]);

        if ($performance->getMainPicture()) {
            $mainImageLinks = $this->mediaController->getMediumFormatsAction($performance->getMainPicture());
            $performance->mainPictureThumbnails = $mainImageLinks;
        }
    }
}
