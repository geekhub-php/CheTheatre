<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Media;

/**
 *
 * @ORM\Table(name="Performance")
 * @ORM\Entity
 *
 */
class Performance

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
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=45)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $description;

    /**
     * @var /Datetime
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     *
     */
    private $premiere;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="performance", cascade={"persist"})
     */
    private $medias;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Affiche", mappedBy="performance", cascade={"persist"})
     */
    private $affiches;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->affiches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $title
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
     * @param string $description
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
     * @param \DateTime $premiere
     * @return Performance
     */
    public function setPremiere($premiere)
    {
        $this->premiere = $premiere;
        return $this;
    }

    /**
     * Set media
     * @param media $media
     * @return Performance
     */
    public function setMedia(Media $media)
    {
        $this->medias[] = $media;
    }

    /**
     * Get medias
     *
     * @return array
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set affiche
     * @param affiche $affiche
     * @return Performance
     */
    public function setAffiche(Affiche $affiche)
    {
        $this->affiches[] = $affiche;
    }

    /**
     * Get affiches
     *
     * @return array
     */
    public function getAffiches()
    {
        return $this->affiches;
    }
}