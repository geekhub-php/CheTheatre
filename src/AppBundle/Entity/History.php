<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\HistoryTranslation")
 * @ExclusionPolicy("all")
 */
class History extends AbstractPersonalTranslatable  implements TranslatableInterface
{
    use TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     * @Type("string")
     * @Expose
     */
    private $text;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="mainPicture_id", referencedColumnName="id")
     */
    private $mainPicture;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("mainPicture")
     */
    public $mainPictureThumbnails;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="history_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="history_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="galleryHasMedia_id",referencedColumnName="id")}
     *     )
     */
    private $galleryHasMedia;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("gallery")
     */
    public $galleryHasMediaThumbnails;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Type("DateTime")
     */
    private $dateTime;

    /**
     * @var int
     *
     * @Type("integer")
     * @Expose
     * @Accessor(getter="getYear")
     */
    private $year;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\HistoryTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->galleryHasMedia = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Unset translations
     *
     * @return History
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
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
     * @param  string  $title
     * @return History
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Set text
     *
     * @param  string  $text
     * @return History
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
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param  string  $slug
     * @return History
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * Set mainPicture
     *
     * @param  \Application\Sonata\MediaBundle\Entity\Media $mainPicture
     * @return History
     */
    public function setMainPicture(\Application\Sonata\MediaBundle\Entity\Media $mainPicture = null)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Add galleryHasMedia
     *
     * @param  \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     * @return History
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
     * Set dateTime
     *
     * @param  \DateTime $dateTime
     * @return History
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->getDateTime()->format('Y');
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }
}
