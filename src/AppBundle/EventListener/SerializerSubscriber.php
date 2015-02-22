<?php

namespace AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sonata\MediaBundle\Controller\Api\MediaController;

class SerializerSubscriber implements EventSubscriberInterface
{
    /** @var MediaController  */
    protected $mediaController;

    public function __construct(MediaController $mediaController)
    {
        $this->mediaController = $mediaController;
    }

    /**
     * @inheritdoc
     */
    static public function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\Employee', 'method' => 'onPreEmployeeSerialize'),
        );
    }

    public function onPreEmployeeSerialize(ObjectEvent $event)
    {
        if (!$avatar = $event->getObject()->getAvatar()) {
            return;
        }

        $avatarLinks = $this->mediaController->getMediumFormatsAction($avatar->getId());
        $event->getObject()->setAvatar($avatarLinks);
    }
}
