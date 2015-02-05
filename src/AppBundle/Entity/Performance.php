<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;

/**
 * @ORM\Table(name="performances")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Performance
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
     * @var /Datetime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    private $premiere;

    /**
     * @var PerformanceEvent[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PerformanceEvent", mappedBy="performance", cascade={"persist"}, orphanRemoval=true)
     */
    private $performanceEvents;

    /**
     * @var Role[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Role", mappedBy="performance", cascade={"persist"}, orphanRemoval=true)
     */
    private $roles;

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
        $this->performanceEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param  string      $title
     * @return Performance
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
     * @param  string      $description
     * @return Performance
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
     * Get premiere
     *
     * @return \DateTime
     */
    public function getPremiere()
    {
        return $this->premiere;
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
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add performanceEvent
     *
     * @param  \AppBundle\Entity\PerformanceEvent $performanceEvent
     * @return Performance
     */
    public function addPerformanceEvent(\AppBundle\Entity\PerformanceEvent $performanceEvent)
    {
        $this->performanceEvents[] = $performanceEvent;

        return $this;
    }

    /**
     * Remove performanceEvent
     *
     * @param \AppBundle\Entity\PerformanceEvent $performanceEvent
     */
    public function removePerformanceEvent(\AppBundle\Entity\PerformanceEvent $performanceEvent)
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
     * @param  \AppBundle\Entity\Role $role
     * @return Performance
     */
    public function addRole(\AppBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \AppBundle\Entity\Role $role
     */
    public function removeRole(\AppBundle\Entity\Role $role)
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
}
