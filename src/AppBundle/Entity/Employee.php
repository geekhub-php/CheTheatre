<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;
use AppBundle\Traits\DeletedByTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="employees")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\EmployeeTranslation")
 * @ExclusionPolicy("all")
 */
class Employee extends AbstractPersonalTranslatable  implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;

    const POSITION_ACTOR = 'actor';
    const POSITION_ACTRESS = 'actress';
    const POSITION_THEATRE_DIRECTOR = 'theatre_director';
    const POSITION_ACTING_ARTISTIC_DIRECTOR = 'acting_artistic_director';
    const POSITION_PRODUCTION_DIRECTOR = 'production_director';
    const POSITION_MAIN_ARTIST = 'main_artist';
    const POSITION_COSTUMER = 'costumer';
    const POSITION_ART_DIRECTOR = 'art_director';
    const POSITION_MAIN_CHOREOGPAPHER = 'main_choreographer';
    const POSITION_HEAD_OF_THE_LITERARY_AND_DRAMATIC_PART = 'head_of_the_literary_and_dramatic_part';
    const POSITION_CONDUCTOR = 'conductor';
    const POSITION_ACCOMPANIST = 'accompanist';

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $lastName;

    /**
     * @var /Datetime
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Type("DateTime")
     * @Expose
     */
    private $dob;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $position;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     * @Type("string")
     * @Expose
     */
    private $biography;

    /**
     * @var Role[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Role", mappedBy="employee", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $roles;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="employee_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="employee_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="galleryHasMedia_id",referencedColumnName="id")}
     *     )
     */
    private $galleryHasMedia;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("gallery")
     */
    public $galleryHasMediaThumbnails;

    /**
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $slug;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id")
     */
    private $avatar;

    /**
     * @var array
     * @Expose
     * @Type("array")
     * @SerializedName("avatar")
     */
    public $avatarThumbnails;

    /**
     * @var Datetime
     *
     * @Expose
     * @Type("string")
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string")
     */
    private $createdBy;

    /**
     * @var Datetime
     *
     * @Expose
     * @Type("string")
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string")
     */
    private $updatedBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\EmployeeTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->galleryHasMedia = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Unset translations
     *
     * @return Employee
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
     * @param  string   $firstName
     * @return Employee
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param  string   $lastName
     * @return Employee
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

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
     * @param  \DateTime $dob
     * @return Employee
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
     * @param  string   $position
     * @return Employee
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set biography
     *
     * @param  string   $biography
     * @return Employee
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Add role
     *
     * @param  \AppBundle\Entity\Role $role
     * @return Employee
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
        return $this->getFirstName().' '.$this->getLastName();
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
     * @param  string   $slug
     * @return Employee
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param  mixed $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public static function getPositions()
    {
        return [
            self::POSITION_ACTOR => 'actor',
            self::POSITION_ACTRESS => 'actress',
            self::POSITION_THEATRE_DIRECTOR => 'theatre_director',
            self::POSITION_ACTING_ARTISTIC_DIRECTOR => 'acting_artistic_director',
            self::POSITION_PRODUCTION_DIRECTOR => 'production_director',
            self::POSITION_MAIN_ARTIST => 'main_artist',
            self::POSITION_COSTUMER => 'costumer',
            self::POSITION_ART_DIRECTOR => 'art_director',
            self::POSITION_MAIN_CHOREOGPAPHER => 'main_choreographer',
            self::POSITION_HEAD_OF_THE_LITERARY_AND_DRAMATIC_PART => 'head_of_the_literary_and_dramatic_part',
            self::POSITION_CONDUCTOR => 'conductor',
            self::POSITION_ACCOMPANIST => 'accompanist',
        ];
    }

    /**
     * Add galleryHasMedia
     *
     * @param  \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     * @return Employee
     */
    public function addGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia->removeElement($galleryHasMedia);
    }

    /**
     * Get galleryHasMedia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryHasMedia()
    {
        return $this->galleryHasMedia;
    }

    /**
     * @return Datetime
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param Datetime $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \Datetime $createdBy
     */
    public function setCreatedBy(\Datetime $createdBy)
    {
        $this->createdBy = $createdBy;
    }
}
