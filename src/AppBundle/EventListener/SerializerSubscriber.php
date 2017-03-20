<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\History;
use AppBundle\Entity\Performance;
use AppBundle\Entity\Post;
use AppBundle\Entity\Employee;
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
            [
                'event' => 'serializer.pre_serialize',
                'class' => 'AppBundle\Entity\Employee',
                'method' => 'onPreEmployeeSerialize'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'class' => 'AppBundle\Entity\Performance',
                'method' => 'onPrePerformanceSerialize'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'class' => 'AppBundle\Entity\Post',
                'method' => 'onPrePostSerialize'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'class' => 'AppBundle\Entity\History',
                'method' => 'onPreHistorySerialize'
            ],
        ];
    }

    public function onPreEmployeeSerialize(ObjectEvent $event)
    {
        /** @var Employee $employee */
        $employee = $event->getObject();

        if ($employee->getAvatar()) {
            $avatarLinks = $this->mediaController->getMediumFormatsAction($employee->getAvatar());
            $employee->avatarThumbnails = $avatarLinks;
        }

        $galleryHasMediaLinks = $this->formatGalleries(
            $employee->getGalleryHasMedia()->getValues(),
            $employee->getLocale()
        );

        if (!empty(array_filter($galleryHasMediaLinks))) {
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

        $galleryHasMediaLinks = $this->formatGalleries(
            $performance->getGalleryHasMedia()->getValues(),
            $performance->getLocale()
        );

        if (!empty(array_filter($galleryHasMediaLinks))) {
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

        $galleryHasMediaLinks = $this->formatGalleries($post->getGalleryHasMedia()->getValues(), $post->getLocale());

        if (!empty(array_filter($galleryHasMediaLinks))) {
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

        $galleryHasMediaLinks = $this->formatGalleries(
            $history->getGalleryHasMedia()->getValues(),
            $history->getLocale()
        );

        if (!empty($galleryHasMediaLinks)) {
            $history->galleryHasMediaThumbnails = $galleryHasMediaLinks;
        }
    }

    /**
     * @param $galleries
     * @param $locale
     * @return array
     */
    protected function formatGalleries($galleries, $locale)
    {
        $formatedGaleries = array_filter($galleries, function ($gallery) {
            return $gallery->getMedia();
        });

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
