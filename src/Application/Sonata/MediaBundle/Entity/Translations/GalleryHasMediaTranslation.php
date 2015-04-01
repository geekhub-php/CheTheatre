<?php

namespace Application\Sonata\MediaBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="galleryhasmedia_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_galleryhasmedia_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class GalleryHasMediaTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\GalleryHasMedia", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
