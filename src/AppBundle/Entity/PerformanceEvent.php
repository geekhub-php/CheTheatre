<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;

/**
 * @ORM\Table(name="performance_schedule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerformanceEventRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class PerformanceEvent
{
    use TimestampableTrait;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Performance", inversedBy="performanceEvents")
     * @Type("AppBundle\Entity\Performance")
     * @Expose
     */
    private $performance;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Type("DateTime")
     * @Expose
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
     * @var int
     *
     * @Expose
     * @Accessor(getter="getMonth")
     */
    private $month;

    /**
     * @var int
     *
     * @Expose
     * @Accessor(getter="getDay")
     */
    private $day;

    /**
     * @var string
     *
     * @Expose
     * @Accessor(getter="getTime")
     */
    private $time;

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
}
