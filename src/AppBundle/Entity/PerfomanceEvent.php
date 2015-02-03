<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;

/**
 * @ORM\Table(name="perfomance_schedule")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class PerfomanceEvent
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
     * @var Performance
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Performance", inversedBy="perfomanceEvents", cascade={"persist"})
     */
    private $performances;

    /**
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->performances = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set dateTime
     *
     * @param  \DateTime $dateTime
     * @return PerfomanceEvent
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get performances
     *
     * @return \AppBundle\Entity\Performance
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    /**
     * Set performances
     *
     * @param  \AppBundle\Entity\Performance $performances
     * @return PerfomanceEvent
     */
    public function setPerformances(\AppBundle\Entity\Performance $performances = null)
    {
        $this->performances = $performances;

        return $this;
    }
}
