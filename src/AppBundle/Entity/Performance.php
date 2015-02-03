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
     * @var PerfomanceEvent[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PerfomanceEvent", mappedBy="performances", cascade={"persist"})
     */
    private $perfomanceEvents;

    /**
     * @var Role[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Role", mappedBy="performances", cascade={"persist"})
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
        $this->perfomanceEvents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param  string $title
     * @return Performance
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @param  string $description
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
     * @param  \DateTime $premiere
     * @return Performance
     */
    public function setPremiere($premiere)
    {
        $this->premiere = $premiere;

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
     * @param  string $slug
     * @return Performance
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Add perfomanceEvents
     *
     * @param  \AppBundle\Entity\PerfomanceEvent $perfomanceEvents
     * @return Performance
     */
    public function addPerfomanceEvent(\AppBundle\Entity\PerfomanceEvent $perfomanceEvents)
    {
        $this->perfomanceEvents[] = $perfomanceEvents;

        return $this;
    }

    /**
     * Remove perfomanceEvents
     *
     * @param \AppBundle\Entity\PerfomanceEvent $perfomanceEvents
     */
    public function removePerfomanceEvent(\AppBundle\Entity\PerfomanceEvent $perfomanceEvents)
    {
        $this->perfomanceEvents->removeElement($perfomanceEvents);
    }

    /**
     * Get perfomanceEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerfomanceEvents()
    {
        return $this->perfomanceEvents;
    }

    /**
     * Add roles
     *
     * @param  \AppBundle\Entity\Role $roles
     * @return Performance
     */
    public function addRole(\AppBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \AppBundle\Entity\Role $roles
     */
    public function removeRole(\AppBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
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
}
