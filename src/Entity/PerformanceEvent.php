<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\TimestampableTrait;
use App\Traits\DeletedByTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Groups;
use App\Validator\TwoPerformanceEventsPerDay;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="performance_schedule", indexes={
 *     @ORM\Index(name="date_time_idx", columns={"dateTime"}),
 *     @ORM\Index(name="performance_dateTime_idx", columns={"performance_id", "dateTime"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PerformanceEventRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\PerformanceEventTranslation")
 * @TwoPerformanceEventsPerDay()
 */
class PerformanceEvent extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;

    public static $venues = [
        'venue-philharmonic' => 'venue-philharmonic',
        'venue-kilic-house' => 'venue-kilic-house',
        'venue-ambros-hub' => 'venue-ambros-hub',
        'venue-theatre' => 'venue-theatre',
        'venue-salut' => 'venue-salut',
        'venue-palac_molodi' => 'venue-palac_molodi',
        'venue-center_of_kids_arts' => 'venue-center_of_kids_arts',
        'venue-cherkasy-art-museum' => 'venue-cherkasy-art-museum',
        'venue-nations-friendship' => 'venue-nations-friendship',
        'trc-pioner' => 'trc-pioner',
        "venue-cherkasy-kobzar" => "venue-cherkasy-kobzar",
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Type("integer")
     * @Expose
     */
    private $id;

    /**
     * @var Performance
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Performance", inversedBy="performanceEvents")
     * @Type("App\Entity\Performance")
     * @Expose
     * @Groups({"Default", "poster"})
     */
    private $performance;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Type("DateTime<'Y-m-d\TH:i:s'>")
     * @Expose
     * @Groups({"Default", "poster"})
     */
    private $dateTime;

    /**
     * Place where performance happens
     * @var string
     * @ORM\Column(type="string")
     * @Type("string")
     * @Expose
     * @Groups({"Default", "poster"})
     */
    private $venue;

    /**
     * @var int
     *
     * @Type("integer")
     * @Expose
     * @Accessor(getter="getYear")
     * @Groups({"Default", "poster"})
     */
    private $year;

    /**
     * @var int
     *
     * @Expose
     * @Accessor(getter="getMonth")
     * @Groups({"Default", "poster"})
     */
    private $month;

    /**
     * @var int
     *
     * @Expose
     * @Accessor(getter="getDay")
     * @Groups({"Default", "poster"})
     */
    private $day;

    /**
     * @var string
     *
     * @Expose
     * @Accessor(getter="getTime")
     * @Groups({"Default", "poster"})
     */
    private $time;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Type("string")
     * @Expose
     * @Groups({"Default", "poster"})
     */
    private $buyTicketLink;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Translations\PerformanceEventTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

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
     * @param \DateTime $dateTime
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
     * @param \App\Entity\Performance $performance
     * @return PerformanceEvent
     */
    public function setPerformance(\App\Entity\Performance $performance = null)
    {
        $this->performance = $performance;

        return $this;
    }

    /**
     * Get performance
     *
     * @return \App\Entity\Performance
     */
    public function getPerformance()
    {
        return $this->performance;
    }

    public function __toString()
    {
        if ($this->getDateTime()) {
            return $this->getDateTime()->format('d-m-Y H:i');
        } else {
            return date("F j, Y, g:i a");
        }
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
     * @return string
     */
    public function getVenue()
    {
        return $this->venue;
    }

    public function setVenue(string $venue): self
    {
        $this->venue = $venue;
        return $this;
    }

    public function getBuyTicketLink(): ?string
    {
        return $this->buyTicketLink;
    }

    public function setBuyTicketLink(?string $buyTicketLink): self
    {
        $this->buyTicketLink = $buyTicketLink;
        return $this;
    }
}
