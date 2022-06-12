<?php

namespace App\Entity;

use App\Entity\Translations\AbstractPersonalTranslatable;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Blameable\Traits\BlameableEntity;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractTranslateableStory extends AbstractPersonalTranslatable
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
     * @ORM\Column(type="text", nullable=true)
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
     * @var \App\Entity\Media
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="mainPicture_id", referencedColumnName="id")
     * @Assert\NotBlank()
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
     * @param \App\Entity\Media $mainPicture
     * @return self
     */
    public function setMainPicture(\App\Entity\Media $mainPicture = null)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Get mainPicture
     *
     * @return \App\Entity\Media
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle() ?: '';
    }
}
