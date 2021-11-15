<?php

namespace App\Entity;

use App\Model\LinksTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\TimestampableTrait;
use App\Traits\DeletedByTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;
use App\Validator\MinSizeSliderImage;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use App\Validator\ProducerConstraint;

/**
 * @ORM\Table(name="performances")
 * @ORM\Entity(repositoryClass="App\Repository\PerformanceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\PerformanceTranslation")
 * @ExclusionPolicy("all")
 * @MinSizeSliderImage()
 * @ProducerConstraint()
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
     * @Groups({"Default", "poster"})
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Type("string")
     * @Assert\NotBlank()
     * @Groups({"Default", "poster"})
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
     * @Assert\Valid()
     * @Groups({"Default", "poster"})
     */
    private $mainPicture;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="sliderImage_id", referencedColumnName="id", nullable=true)
     * @Groups({"Default", "poster"})
     */
    private $sliderImage;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("mainPicture")
     * @Groups({"Default", "poster"})
     */
    public $mainPictureThumbnails;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("sliderImage")
     * @Groups({"Default", "poster"})
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
     * @Assert\Valid()
     */
    private $roles;

    /**
     * @var \App\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\GalleryHasMedia", cascade={"persist"}, fetch="EAGER")
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
     * @Groups({"Default", "poster"})
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
     * @var \App\Entity\History
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\History", inversedBy="performances")
     * @Assert\Valid()
     */
    protected $festival;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\RepertoireSeason", inversedBy="performances")
     * @Expose
     * @Serializer\MaxDepth(1)
     * @Assert\Valid()
     */
    private $seasons;

    /**
     * @ORM\Column(type="AudienceEnum", options={"default":"adults"})
     * @DoctrineAssert\Enum(entity="App\Enum\AudienceEnum")
     * @Type("string")
     * @Expose
     * @Assert\Valid()
     */
    private string $audience;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     * @Assert\PositiveOrZero
     * @Type("integer")
     * @Groups({"Default", "poster"})
     * @Expose
     */
    private int $ageLimit;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\PositiveOrZero
     * @Type("integer")
     * @Groups({"Default", "poster"})
     * @Expose
     */
    private int $durationInMin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee", inversedBy="produce")
     * @ORM\JoinColumn(name="producer_id", referencedColumnName="id", onDelete="SET NULL")
     * @Expose
     */
    private ?Employee $producer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Expose
     */
    private ?string $extProducer = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->performanceEvents = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->galleryHasMedia = new ArrayCollection();
        $this->seasons = new ArrayCollection();
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
     * @return Role[]|\Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function __toString()
    {
        return $this->getTitle() ?: '';
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
    public function addGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    public function addGalleryHasMedia(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        return $this->addGalleryHasMedion($galleryHasMedia);
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
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

    /**
     * @return Collection|RepertoireSeason[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(RepertoireSeason $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
        }

        return $this;
    }

    public function removeSeason(RepertoireSeason $season): self
    {
        if ($this->seasons->contains($season)) {
            $this->seasons->removeElement($season);
        }

        return $this;
    }

    public function setAudience(string $audience): self
    {
        $this->audience = $audience;

        return $this;
    }

    public function getAudience(): string
    {
        return $this->audience;
    }

    public function getAgeLimit(): int
    {
        return $this->ageLimit;
    }

    public function setAgeLimit(int $ageLimit): Performance
    {
        $this->ageLimit = $ageLimit;
        return $this;
    }

    public function getDurationInMin(): int
    {
        return $this->durationInMin;
    }

    public function setDurationInMin(int $durationInMin): Performance
    {
        $this->durationInMin = $durationInMin;
        return $this;
    }

    public function getProducer(): ?Employee
    {
        return $this->producer;
    }

    public function setProducer(?Employee $producer): Performance
    {
        $this->producer = $producer;
        return $this;
    }

    public function getExtProducer(): ?string
    {
        return $this->extProducer;
    }

    public function setExtProducer(?string $extProducer): Performance
    {
        $this->extProducer = $extProducer;
        return $this;
    }
}
