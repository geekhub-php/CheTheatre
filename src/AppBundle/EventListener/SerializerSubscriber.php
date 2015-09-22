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
use Symfony\Component\Translation\TranslatorInterface;

class SerializerSubscriber implements EventSubscriberInterface
{
    /** @var MediaController  */
    protected $mediaController;

    /** @var Router */
    protected $router;

    /** @var  TranslatorInterface */
    protected $translator;

    public function __construct(MediaController $mediaController, Router $router, TranslatorInterface $translator)
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
        /** @var Employee $employee */
        $employee = $event->getObject();

        if ($employee->getAvatar()) {
            $avatarLinks = $this->mediaController->getMediumFormatsAction($employee->getAvatar());
            $employee->avatarThumbnails = $avatarLinks;
        }

        if ($galleryHasMediaLinks = $this->formatGalleries($employee->getGalleryHasMedia()->getValues(), $employee->getLocale())) {
            $employee->galleryHasMediaThumbnails = $galleryHasMediaLinks;
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

        if ($galleryHasMediaLinks = $this->formatGalleries($performance->getGalleryHasMedia()->getValues(), $performance->getLocale())) {
            $performance->galleryHasMediaThumbnails = $galleryHasMediaLinks;
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

        if ($galleryHasMediaLinks = $this->formatGalleries($post->getGalleryHasMedia()->getValues(), $post->getLocale())) {
            $post->galleryHasMediaThumbnails = $galleryHasMediaLinks;
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

        if ($galleryHasMediaLinks = $this->formatGalleries($history->getGalleryHasMedia()->getValues(), $history->getLocale())) {
            $history->galleryHasMediaThumbnails = $galleryHasMediaLinks;
        }
    }

    protected function formatGalleries($galleries, $locale)
    {
        $formatedGaleries = array_filter($galleries, function($gallery) { return $gallery->getMedia(); });
        $formatedGaleries = array_map(function ($gallery) use ($locale) {
            return [
                'title' => $gallery->getTranslation('title', $locale) ?: $gallery->getTitle(),
                'decription' => $gallery->getTranslation('description', $locale) ?: $gallery->getDescription(),
                'images' => $this->mediaController->getMediumFormatsAction($gallery->getMedia()),
            ];
        }, $formatedGaleries);

        return $formatedGaleries;
    }
}
