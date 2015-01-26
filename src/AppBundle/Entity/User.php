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
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=45)
     */
    private $LastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $MiddleName;

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
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Role", mappedBy="performance", cascade={"persist"})
     */
    private $roles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get LastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->LastName;
    }

    /**
     * Set LastName
     *
     * @param string $LastName
     * @return User
     */
    public function setLastName($LastName)
    {
        $this->LastName = $LastName;
        return $this;
    }

    /**
     * Get MiddleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->MiddleName;
    }

    /**
     * Set MiddleName
     *
     * @param string $MiddleName
     * @return User
     */
    public function setMiddleName($MiddleName)
    {
        $this->MiddleName = $MiddleName;
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

    /**
     * Set role
     * @param role $role
     * @return User
     */
    public function setRole(Role $role)
    {
        $this->roles[] = $role;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}