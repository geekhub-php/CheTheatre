<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\History;
use AppBundle\Entity\Performance;
use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Post;
use AppBundle\Entity\Employee;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sonata\MediaBundle\Controller\Api\MediaController;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\LoggingTranslator;

class SerializerSubscriber implements EventSubscriberInterface
{
    /** @var MediaController  */
    protected $mediaController;

    /** @var Router */
    protected $router;

    /** @var  LoggingTranslator */
    protected $translator;

    public function __construct(MediaController $mediaController, Router $router, LoggingTranslator $translator)
    {
        $this->mediaController = $mediaController;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\Employee', 'method' => 'onPreEmployeeSerialize'],
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\Performance', 'method' => 'onPrePerformanceSerialize'],
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\PerformanceEvent', 'method' => 'onPrePerformanceEventSerialize'],
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\Post', 'method' => 'onPrePostSerialize'],
            ['event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\History', 'method' => 'onPreHistorySerialize'],
        ];
    }

    public function onPrePerformanceEventSerialize(ObjectEvent $event)
    {
        /** @var PerformanceEvent $performanceEvent */
        $performanceEvent = $event->getObject();
        $performanceEvent->setVenue($this->translator->trans($performanceEvent->getVenue()));
    }

    public function onPreEmployeeSerialize(ObjectEvent $event)
    {
        $employee = $event->getObject();

        if ($employee->getAvatar()) {
            $avatarLinks = $this->mediaController->getMediumFormatsAction($employee->getAvatar());
            $employee->avatarThumbnails = $avatarLinks;
        }

        if ($employee->getGalleryHasMedia()->getValues()) {
            foreach ($employee->getGalleryHasMedia()->getValues() as $gallery) {
                $galleryHasMediaLinks[] = [
                    'title' => $gallery->getTranslation('title', $employee->getLocale()) ?: $gallery->getTitle(),
                    'decription' => $gallery->getTranslation('description', $employee->getLocale()) ?: $gallery->getDescription(),
                    'images' => $this->mediaController->getMediumFormatsAction($gallery->getMedia()),
                ]
                ;
                $employee->galleryHasMediaThumbnails = $galleryHasMediaLinks;
            }
        }
    }

    public function onPrePerformanceSerialize(ObjectEvent $event)
    {
        /** @var Performance $performance */
        $performance = $event->getObject();

        if ($performance->getMainPicture()) {
            $mainImageLinks = $this->mediaController->getMediumFormatsAction($performance->getMainPicture());
            $performance->mainPictureThumbnails = $mainImageLinks;
        }

        if ($performance->getSliderImage()) {
            $sliderImageLinks = $this->mediaController->getMediumFormatsAction($performance->getSliderImage());
            $performance->sliderImageThumbnails = $sliderImageLinks;
        }

        if ($performance->getGalleryHasMedia()->getValues()) {
            foreach ($performance->getGalleryHasMedia()->getValues() as $gallery) {
                $galleryHasMediaLinks[] = [
                    'title' => $gallery->getTranslation('title', $performance->getLocale()) ?: $gallery->getTitle(),
                    'decription' => $gallery->getTranslation('description', $performance->getLocale()) ?: $gallery->getDescription(),
                    'images' => $this->mediaController->getMediumFormatsAction($gallery->getMedia()),
                ]
                ;
                $performance->galleryHasMediaThumbnails = $galleryHasMediaLinks;
            }
        }
    }

    public function onPrePostSerialize(ObjectEvent $event)
    {
        /** @var Post $post */
        $post = $event->getObject();

        if ($post->getMainPicture()) {
            $mainImageLinks = $this->mediaController->getMediumFormatsAction($post->getMainPicture());
            $post->mainPictureThumbnails = $mainImageLinks;
        }

        if ($post->getGalleryHasMedia()->getValues()) {
            foreach ($post->getGalleryHasMedia()->getValues() as $gallery) {
                $galleryHasMediaLinks[] = [
                    'title' => $gallery->getTranslation('title', $post->getLocale()) ?: $gallery->getTitle(),
                    'decription' => $gallery->getTranslation('description', $post->getLocale()) ?: $gallery->getDescription(),
                    'images' => $this->mediaController->getMediumFormatsAction($gallery->getMedia()),
                ]
                ;
                $post->galleryHasMediaThumbnails = $galleryHasMediaLinks;
            }
        }
    }

    public function onPreHistorySerialize(ObjectEvent $event)
    {
        /** @var History $history */
        $history = $event->getObject();

        if ($history->getMainPicture()) {
            $mainImageLinks = $this->mediaController->getMediumFormatsAction($history->getMainPicture());
            $history->mainPictureThumbnails = $mainImageLinks;
        }

        if ($history->getGalleryHasMedia()->getValues()) {
            foreach ($history->getGalleryHasMedia()->getValues() as $gallery) {
                $galleryHasMediaLinks[] = [
                    'title' => $gallery->getTranslation('title', $history->getLocale()) ?: $gallery->getTitle(),
                    'decription' => $gallery->getTranslation('description', $history->getLocale()) ?: $gallery->getDescription(),
                    'images' => $this->mediaController->getMediumFormatsAction($gallery->getMedia()),
                ]
                ;
                $history->galleryHasMediaThumbnails = $galleryHasMediaLinks;
            }
        }
    }
}
