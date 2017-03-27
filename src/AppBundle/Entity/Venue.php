<?php

namespace AppBundle\Entity;

use AppBundle\Traits\DeletedByTrait;
use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Document\Translation;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="venue")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenueRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\VenueTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class Venue extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, DeletedByTrait;

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
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose()
     */
    protected $title;

    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Serializer\Groups({"get_ticket"})
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $address;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="text", nullable=true)
     * @Type("string")
     * @Expose()
     */
    protected $hallTemplate;

    /**
     * @var ArrayCollection|Translation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\VenueTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var ArrayCollection|PerformanceEvent[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\PerformanceEvent",
     *     mappedBy="venue",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $performanceEvents;

    /**
     * @var Collection|VenueSector[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\VenueSector",
     *     mappedBy="venue",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $venueSectors;

    /**
     * @var Collection|PriceCategory[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\PriceCategory",
     *     mappedBy="venue",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $priceCategories;

    /**
     * Venue constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->performanceEvents = new ArrayCollection();
        $this->venueSectors = new ArrayCollection();
        $this->priceCategories = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Venue
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string $title
     * @return Venue
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param  string $address
     * @return Venue
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getHallTemplate()
    {
        return $this->hallTemplate;
    }

    /**
     * @param  string $hallTemplate
     * @return Venue
     */
    public function setHallTemplate($hallTemplate)
    {
        $this->hallTemplate = $hallTemplate;

        return $this;
    }

    /**
     * @param  PerformanceEvent $performanceEvent
     * @return Venue
     */
    public function addPerformanceEvent(PerformanceEvent $performanceEvent)
    {
        $this->performanceEvents[] = $performanceEvent;

        return $this;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     */
    public function removePerformanceEvent(PerformanceEvent $performanceEvent)
    {
        $this->performanceEvents->removeElement($performanceEvent);
    }

    /**
     * @return Collection
     */
    public function getPerformanceEvents()
    {
        return $this->performanceEvents;
    }

    /**
     * @param  VenueSector $venueSector
     * @return Venue
     */
    public function addVenueSector(VenueSector $venueSector)
    {
        $this->venueSectors[] = $venueSector;

        return $this;
    }

    /**
     * @param VenueSector $venueSector
     */
    public function removeVenueSector(VenueSector $venueSector)
    {
        $this->venueSectors->removeElement($venueSector);
    }

    /**
     * @return Collection
     */
    public function getVenueSector()
    {
        return $this->venueSectors;
    }


    /**
     * @param  PriceCategory $priceCategory
     * @return Venue
     */
    public function addPriceCategory(PriceCategory $priceCategory)
    {
        $this->priceCategories[] = $priceCategory;

        return $this;
    }

    /**
     * @param PriceCategory $priceCategory
     */
    public function removePriceCategory(PriceCategory $priceCategory)
    {
        $this->priceCategories->removeElement($priceCategory);
    }

    /**
     * @return Collection
     */
    public function getPriceCategories()
    {
        return $this->priceCategories;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @param PriceCategory[]|Collection $priceCategory
     */
    public function setPriceCategories($priceCategory)
    {
        $this->priceCategories = $priceCategory;
    }
}
