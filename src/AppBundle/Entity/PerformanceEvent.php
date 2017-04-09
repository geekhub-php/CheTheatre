<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;
use AppBundle\Traits\DeletedByTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use AppBundle\Validator\TwoPerformanceEventsPerDay;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="performance_schedule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerformanceEventRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\PerformanceEventTranslation")
 * @TwoPerformanceEventsPerDay()
 */
class PerformanceEvent extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("integer")
     * @Expose
     */
    private $id;

    /**
     * @var Performance
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Performance", inversedBy="performanceEvents")
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("AppBundle\Entity\Performance")
     * @Expose
     */
    private $performance;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("DateTime")
     * @Expose
     */
    private $dateTime;

    /**
     * @var Venue
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Venue", inversedBy="performanceEvents")
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("AppBundle\Entity\Venue")
     * @Expose
     */
    private $venue;

    /**
     * @var int
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("integer")
     * @Expose
     * @Accessor(getter="getYear")
     */
    private $year;

    /**
     * @var int
     *
     * @Serializer\Groups({"get_ticket"})
     * @Expose
     * @Accessor(getter="getMonth")
     */
    private $month;

    /**
     * @var int
     *
     * @Serializer\Groups({"get_ticket"})
     * @Expose
     * @Accessor(getter="getDay")
     */
    private $day;

    /**
     * @var string
     *
     * @Serializer\Groups({"get_ticket"})
     * @Expose
     * @Accessor(getter="getTime")
     */
    private $time;

    /**
     * @var string
     *
     * @Serializer\Groups({"get_ticket"})
     * @ORM\Column(type="string", length=10,  nullable=true)
     * @Assert\Length(max="10", min="3")
     * @Type("string")
     * @Expose
     */
    private $seriesNumber;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("DateTime")
     * @Expose
     */
    private $seriesDate;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("boolean")
     * @Expose
     */
    private $enableSale;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\RowsForSale",
     *     inversedBy="performanceEvent",
     *     cascade={"persist","detach","merge"}
     * )
     * @Serializer\Groups({"get_ticket"})
     * @Type("array")
     * @Expose
     */
    protected $rowsForSale;

    /**
     * @var ArrayCollection|Translation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\PerformanceEventTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var ArrayCollection|PriceCategory[]
     *
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\PriceCategory",
     *     mappedBy="performanceEvent",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $priceCategories;

    public function __construct()
    {
        parent::__construct();
        $this->priceCategories = new ArrayCollection();
        $this->seriesDate = new \DateTime();
        $this->rowsForSale = new ArrayCollection();
    }

    /**
     * Unset translations
     *
     * @return PerformanceEvent
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
     * Set dateTime
     *
     * @param  \DateTime        $dateTime
     * @return PerformanceEvent
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
     * Set performance
     *
     * @param  \AppBundle\Entity\Performance $performance
     * @return PerformanceEvent
     */
    public function setPerformance(\AppBundle\Entity\Performance $performance = null)
    {
        $this->performance = $performance;

        return $this;
    }

    /**
     * Get performance
     *
     * @return \AppBundle\Entity\Performance
     */
    public function getPerformance()
    {
        return $this->performance;
    }

    public function __toString()
    {
        if ($this->getDateTime()) {
            return $this->getDateTime()->format('d-m-Y H:i');
        }

        return date("F j, Y, g:i a");
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

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->getDateTime()->format('n');
    }

    /**
     * @param int $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->getDateTime()->format('j');
    }

    /**
     * @param int $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->getDateTime()->format('G:i');
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return Venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @param Venue $venue
     * @return PerformanceEvent
     */
    public function setVenue(Venue $venue)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * @param  PriceCategory $priceCategory
     * @return PerformanceEvent
     */
    public function addPriceCategory(PriceCategory $priceCategory)
    {
        $this->priceCategories[] = $priceCategory;

        return $this;
    }

    /**
     * @param PriceCategory $priceCategory
     */
    public function removePriceCategories(PriceCategory $priceCategory)
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
     * @param PriceCategory[]|ArrayCollection $priceCategory
     */
    public function setPriceCategories($priceCategory)
    {
        $this->priceCategories[] = $priceCategory;
    }

    /**
     * @return string
     */
    public function getSeriesNumber()
    {
        return $this->seriesNumber;
    }

    /**
     * @param string $seriesNumber
     */
    public function setSeriesNumber($seriesNumber)
    {
        $this->seriesNumber = $seriesNumber;
    }

    /**
     * @return \DateTime
     */
    public function getSeriesDate()
    {
        return $this->seriesDate;
    }

    /**
     * @param \DateTime $seriesDate
     */
    public function setSeriesDate($seriesDate)
    {
        $this->seriesDate = $seriesDate;
    }

    /**
     * @return boolean
     */
    public function isEnableSale()
    {
        return $this->enableSale;
    }

    /**
     * @param boolean $enableSale
     */
    public function setEnableSale($enableSale)
    {
        $this->enableSale = $enableSale;
    }

    /**
     * @return Collection
     */
    public function getRowsForSale()
    {
        return $this->rowsForSale;
    }

    /**
     * @param  RowsForSale $rowsForSale
     * @return PerformanceEvent
     */
    public function addRowsForSale(RowsForSale $rowsForSale)
    {
        $this->rowsForSale[] = $rowsForSale;

        return $this;
    }

    /**
     * @param RowsForSale $rowsForSale
     */
    public function removeRowsForSale(RowsForSale $rowsForSale)
    {
        $this->rowsForSale->removeElement($rowsForSale);
    }
}
