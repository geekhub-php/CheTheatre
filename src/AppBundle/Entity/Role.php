<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role
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
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Performance
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Performance", inversedBy="roles", cascade={"persist"})
     */
    private $performances;

    /**
     * @var Employee
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee", inversedBy="roles", cascade={"persist"})
     */
    private $employees;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->performances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param  string $title
     * @return Role
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set description
     *
     * @param  string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Set slug
     *
     * @param  string $slug
     * @return Role
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * Set performances
     *
     * @param  \AppBundle\Entity\Performance $performances
     * @return Role
     */
    public function setPerformances(\AppBundle\Entity\Performance $performances = null)
    {
        $this->performances = $performances;

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
     * Set employees
     *
     * @param  \AppBundle\Entity\Employee $employees
     * @return Role
     */
    public function setEmployees(\AppBundle\Entity\Employee $employees = null)
    {
        $this->employees = $employees;

        return $this;
    }

    /**
     * Get employees
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployees()
    {
        return $this->employees;
    }
}
