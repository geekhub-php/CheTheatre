<?php

namespace App\Entity;

use App\Model\LinksTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\TimestampableTrait;
use App\Traits\DeletedByTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use App\Validator\MinSizeSliderImage;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="performances")
 * @ORM\Entity(repositoryClass="App\Repository\PerformanceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\PerformanceTranslation")
 * @ExclusionPolicy("all")
 * @MinSizeSliderImage()
 */
class Performance extends AbstractPersonalTranslatable  implements TranslatableInterface
{
    use TimestampableTrait, LinksTrait, BlameableEntity, DeletedByTrait;

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
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Type("string")
     * @Expose
     */
    private $type;

    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="text", nullable=true)
     * @Type("string")
     * @Expose
     */
    private $description;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Type("DateTime")
     * @Expose
     */
    private $premiere;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="mainPicture_id", referencedColumnName="id", nullable=true)
     */
    private $mainPicture;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="sliderImage_id", referencedColumnName="id", nullable=true)
     */
    private $sliderImage;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("mainPicture")
     */
    public $mainPictureThumbnails;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("sliderImage")
     */
    public $sliderImageThumbnails;

    /**
     * @var PerformanceEvent[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\PerformanceEvent", mappedBy="performance", cascade={"persist"}, orphanRemoval=true)
     */
    private $performanceEvents;

    /**
     * @var Role[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="performance", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $roles;

    /**
     * @var \App\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="performance_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="performance_id",referencedColumnName="id")},
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
     *     targetEntity="App\Entity\Translations\PerformanceTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var \App\Entity\Festival
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\History", inversedBy="performances")
     */
    protected $festival;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->performanceEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->galleryHasMedia = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Unset translations
     *
     * @return Performance
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param  string      $type
     * @return Performance
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @param  string      $description
     * @return Performance
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get premiere
     *
     * @return \DateTime
     */
    public function getPremiere()
    {
        return $this->premiere;
    }

    /**
     * Set premiere
     *
     * @param  \DateTime   $premiere
     * @return Performance
     */
    public function setPremiere($premiere)
    {
        $this->premiere = $premiere;

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
     * Set mainPicture
     *
     * @param  \App\Entity\Media $mainPicture
     * @return Performance
     */
    public function setMainPicture(\App\Entity\Media $mainPicture = null)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Get sliderImage
     *
     * @return \App\Entity\Media
     */
    public function getSliderImage()
    {
        return $this->sliderImage;
    }

    /**
     * Set sliderImage
     *
     * @param  \App\Entity\Media $sliderImage
     * @return Performance
     */
    public function setSliderImage(\App\Entity\Media $sliderImage = null)
    {
        $this->sliderImage = $sliderImage;

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
     * Set slug
     *
     * @param  string      $slug
     * @return Performance
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Add performanceEvent
     *
     * @param  \App\Entity\PerformanceEvent $performanceEvent
     * @return Performance
     */
    public function addPerformanceEvent(\App\Entity\PerformanceEvent $performanceEvent)
    {
        $this->performanceEvents[] = $performanceEvent;

        return $this;
    }

    /**
     * Remove performanceEvent
     *
     * @param \App\Entity\PerformanceEvent $performanceEvent
     */
    public function removePerformanceEvent(\App\Entity\PerformanceEvent $performanceEvent)
    {
        $this->performanceEvents->removeElement($performanceEvent);
    }

    /**
     * Get performanceEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerformanceEvents()
    {
        return $this->performanceEvents;
    }

    /**
     * Add role
     *
     * @param  \App\Entity\Role $role
     * @return Performance
     */
    public function addRole(\App\Entity\Role $role)
    {
        $role->setPerformance($this);
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \App\Entity\Role $role
     */
    public function removeRole(\App\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
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
     * @param  string      $title
     * @return Performance
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Add galleryHasMedia
     *
     * @param  \App\Entity\GalleryHasMedia $galleryHasMedia
     * @return Performance
     */
    public function addGalleryHasMedia(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedia(\App\Entity\GalleryHasMedia $galleryHasMedia)
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
     * @return Festival
     */
    public function getFestival()
    {
        return $this->festival;
    }

    /**
     * @param Festival $festival
     * @return $this
     */
    public function setFestival($festival)
    {
        $this->festival = $festival;

        return $this;
    }
}
