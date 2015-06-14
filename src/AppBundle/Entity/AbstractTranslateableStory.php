<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Blameable\Traits\BlameableEntity;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractTranslateableStory extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $shortDescription;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $text;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="mainPicture_id", referencedColumnName="id")
     */
    protected $mainPicture;

    /**
     * @var array
     *
     * @Serializer\Expose
     * @Serializer\Type("array")
     * @Serializer\SerializedName("mainPicture")
     */
    public $mainPictureThumbnails;

    /**
     * @var array
     *
     * @Serializer\Expose
     * @Serializer\Type("array")
     * @Serializer\SerializedName("gallery")
     */
    public $galleryHasMediaThumbnails;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $slug;

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
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return self
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set mainPicture
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mainPicture
     * @return self
     */
    public function setMainPicture(\Application\Sonata\MediaBundle\Entity\Media $mainPicture = null)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Get mainPicture
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * Add galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     * @return self
     */
    public function addGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia->removeElement($galleryHasMedia);
    }

    /**
     * Get galleryHasMedia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGalleryHasMedia()
    {
        return $this->galleryHasMedia;
    }

    /**
     * Add tags
     *
     * @param \AppBundle\Entity\Tag $tags
     * @return self
     */
    public function addTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
