<?php

namespace App\Entity;

use App\Traits\DeletedByTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\HistoryTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Serializer\ExclusionPolicy("all")
 */
class History extends AbstractTranslateableStory
{
    use DeletedByTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime")
     */
    protected $dateTime;

    /**
     * @var int
     *
     * @Serializer\Type("integer")
     * @Serializer\Expose
     * @Serializer\Accessor(getter="getYear")
     */
    protected $year;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Translations\HistoryTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var \App\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="history_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="history_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="galleryHasMedia_id",referencedColumnName="id")}
     * )
     */
    protected $galleryHasMedia;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback = "getTypes", message = "choose_valid_type")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @Serializer\Expose
     * @ORM\OneToMany(targetEntity="App\Entity\Performance", mappedBy="festival", orphanRemoval=true)
     */
    protected $performances;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->galleryHasMedia = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Unset translations
     *
     * @return History
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
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
     * Add galleryHasMedia
     *
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     * @return self
     */
    public function addGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public static function getTypes()
    {
        return ['history' => 'history', 'festival' => 'festival'];
    }
}
