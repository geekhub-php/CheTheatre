<?php

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia as BaseGalleryHasMedia;
use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="media__gallery_media")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Application\Sonata\MediaBundle\Entity\Translations\GalleryHasMediaTranslation")
 */
class GalleryHasMedia extends BaseGalleryHasMedia implements TranslatableInterface
{
    use PersonalTranslatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Application\Sonata\MediaBundle\Entity\Translations\GalleryHasMediaTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * Unset translations
     *
     * @return GalleryHasMedia
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string          $title
     * @return GalleryHasMedia
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param  string          $description
     * @return GalleryHasMedia
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
