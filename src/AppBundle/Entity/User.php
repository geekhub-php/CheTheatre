<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Media;

/**
 *
 * @ORM\Table(name="User")
 * @ORM\Entity
 *
 */
class User
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
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=45)
     */
    private $Lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $Middlename;

    /**
     * @var /Datetime
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     *
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     */
    private $position;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="user", cascade={"persist"})
     */
    private $medias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get firstname
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get Lastname
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->Lastname;
    }

    /**
     * Set Lastname
     *
     * @param string $Lastname
     * @return User
     */
    public function setLastName($Lastname)
    {
        $this->Lastname = $Lastname;
        return $this;
    }

    /**
     * Get Middlename
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->Middlename;
    }

    /**
     * Set Middlename
     *
     * @param string $Middlename
     * @return User
     */
    public function setMiddleName($Middlename)
    {
        $this->Middlename = $Middlename;
        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Set media
     * @param media $media
     * @return User
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
}