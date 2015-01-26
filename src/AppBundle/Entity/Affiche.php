<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="affiches")
 * @ORM\Entity
 */
class Affiche
{
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Performance", inversedBy="affiches", cascade={"persist"})
     */
    private $performance;

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
     * Get performance
     *
     * @return Performance
     */
    public function getPerformance()
    {
        return $this->performance;
    }

    /**
     * Set performance
     *
     * @param Performance $performance
     * @return affiche
     */
    public function setPerformance(Performance $performance = null)
    {
        $this->performance = $performance;

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
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return affiche
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
