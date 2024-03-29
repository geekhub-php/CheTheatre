<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\TimestampableTrait;
use App\Traits\DeletedByTrait;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="employees")
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\EmployeeTranslation")
 * @ExclusionPolicy("all")
 */
class Employee extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;

    const POSITION_ACTOR = 'actor';
    const POSITION_ACTRESS = 'actress';
    const POSITION_THEATRE_DIRECTOR = 'theatre_director'; //
    const POSITION_THEATRE_DIRECTOR_ART_DIRECTOR = 'theatre_director_art_director'; //
    const POSITION_ACTING_ARTISTIC_DIRECTOR = 'acting_artistic_director'; //
    const POSITION_PRODUCTION_DIRECTOR = 'production_director';
    const POSITION_MAIN_ARTIST = 'main_artist';
    const POSITION_COSTUMER = 'costumer';
    const POSITION_ARTISTIC_DIRECTOR = 'artistic_director';
    const POSITION_MAIN_CHOREOGPAPHER = 'main_choreographer';
    const POSITION_HEAD_OF_THE_LITERARY_AND_DRAMATIC_PART = 'head_of_the_literary_and_dramatic_part';
    const POSITION_CONDUCTOR = 'conductor';
    const POSITION_ACCOMPANIST = 'accompanist';
    const POSITION_HEAD_CHOREOGRAPHER = 'head_choreographer';
    const POSITION_ART_DIRECTOR = 'art_director';
    const POSITION_STAGED = 'staged';
    const POSITION_ACCOMPANIST_SINGING_CLASS = 'accompanist_singing_class';
    const POSITION_HEAD_OF_TROUPE = 'head_of_troupe';
    const POSITION_HEAD_OF_ARTISTIC_STAGING_PART = 'head_of_artistic_staging_part';
    const POSITION_STAGE_MANAGER = 'stage_manager';
    const POSITION_LEADING_ARTIST_SCENE = 'leading_artist_scene';
    const POSITION_ACTOR_HIGHER_CATEGORY = 'actor_higher_category';
    const POSITION_ACTOR_FIRST_CATEGORY = 'actor_first_category';
    const POSITION_ACTOR_SINGER_SOLOIST_HIGHER_CATEGORY = 'actor_singer_soloist_higher_category';

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
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="employee", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $roles;

    /**
     * @var \App\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\GalleryHasMedia", cascade={"persist"})
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
     * @var Media
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media", cascade={"persist"})
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Translations\EmployeeTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var integer
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer", options={"default": 0 })
     * @Expose
     */
    private int $orderPosition;

    /**
     * @var Collection|Performance[]
     * @ORM\OneToMany(targetEntity="App\Entity\Performance", mappedBy="producer")
     */
    private Collection $produce;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity=EmployeeGroup::class, inversedBy="employees")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private ?EmployeeGroup $employeeGroup = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->roles = new ArrayCollection();
        $this->galleryHasMedia = new ArrayCollection();
        $this->produce = new ArrayCollection();
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
     * @param string $firstName
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
     * @param string $lastName
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
     * @param \DateTime $dob
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
     * @param string $position
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
     * @param string $biography
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
     * @param \App\Entity\Role $role
     * @return Employee
     */
    public function addRole(\App\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \App\Entity\Role $role
     */
    public function removeRole(\App\Entity\Role $role)
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
        return $this->getFirstName() . ' ' . $this->getLastName();
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
     * @param string $slug
     * @return Employee
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
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
            self::POSITION_THEATRE_DIRECTOR_ART_DIRECTOR => 'theatre_director_art_director',
            self::POSITION_ACTING_ARTISTIC_DIRECTOR => 'acting_artistic_director',
            self::POSITION_PRODUCTION_DIRECTOR => 'production_director',
            self::POSITION_MAIN_ARTIST => 'main_artist',
            self::POSITION_COSTUMER => 'costumer',
            self::POSITION_ARTISTIC_DIRECTOR => 'artistic_director',
            self::POSITION_MAIN_CHOREOGPAPHER => 'main_choreographer',
            self::POSITION_HEAD_OF_THE_LITERARY_AND_DRAMATIC_PART => 'head_of_the_literary_and_dramatic_part',
            self::POSITION_CONDUCTOR => 'conductor',
            self::POSITION_ACCOMPANIST => 'accompanist',
            self::POSITION_HEAD_CHOREOGRAPHER => 'head_choreographer',
            self::POSITION_ART_DIRECTOR => 'art_director',
            self::POSITION_STAGED => 'staged',
            self::POSITION_ACCOMPANIST_SINGING_CLASS => 'accompanist_singing_class',
            self::POSITION_HEAD_OF_TROUPE => 'head_of_troupe',
            self::POSITION_HEAD_OF_ARTISTIC_STAGING_PART => 'head_of_artistic_staging_part',
            self::POSITION_STAGE_MANAGER => 'stage_manager',
            self::POSITION_LEADING_ARTIST_SCENE => 'leading_artist_scene',
            self::POSITION_ACTOR_HIGHER_CATEGORY => 'actor_higher_category',
            self::POSITION_ACTOR_FIRST_CATEGORY => 'actor_first_category',
            self::POSITION_ACTOR_SINGER_SOLOIST_HIGHER_CATEGORY => 'actor_singer_soloist_higher_category',
            'Ballet dancer man' => 'Ballet dancer man',
            'Orchestra artist man' => 'Orchestra artist man',
            'Ballet dancer woman' => 'Ballet dancer woman',
            'Orchestra artist woman' => 'Orchestra artist woman',
            'Chief Administrator' => 'Chief Administrator',
            'Chief Accountant' => 'Chief Accountant',
            'Chief director' => 'Chief director',
            'Chief Engineer' => 'Chief Engineer',
            'Conductor' => 'Conductor',
            'Head of the artistic and staging part' => 'Head of the artistic and staging part',
            'Heads assistant' => 'Heads assistant',
            'Head of the literary and dramatic part' => 'Head of the literary and dramatic part',
            'Concertmaster' => 'Concertmaster',
            'Head of the sham shop' => 'Head of the sham shop',
            'Head of the bureau' => 'Head of the bureau',
            'Head of the decoration shop' => 'Head of the decoration shop',
            'Head of the sound shop' => 'Head of the sound shop',
            'Head of the costume shop' => 'Head of the costume shop',
            'Head of the machine shop' => 'Head of the machine shop',
            'Head of the lighting shop' => 'Head of the lighting shop',
            'Head of the hairdressing shop' => 'Head of the hairdressing shop',
            'Head of the womens clothing sewing shop' => 'Head of the womens clothing sewing shop',
            'Head of the mens clothing sewing shop' => 'Head of the mens clothing sewing shop',
            'Head of the props shop' => 'Head of the props shop',
            'Head of the carpentry shop' => 'Head of the carpentry shop',
            'Property man' => 'Property man',
            'Senior administrator' => 'Senior administrator',
            'Costume designer' => 'Costume designer',
            'Lighting artist' => 'Lighting artist',
            'Decorator' => 'Decorator',
            'Leading accountant' => 'Leading accountant',
            'Senior Economist' => 'Senior Economist',
            'Stage engenier' => 'Stage engenier',
            'Leading design engineer for particularly complex scenery' => 'Leading design engineer for particularly complex scenery',
            'Electric illuminator' => 'Electric illuminator',
            'Soundman' => 'Soundman',
            'Recordist' => 'Recordist',
            'Dummy' => 'Dummy',
            'Cutter' => 'Cutter',
            'Dresser' => 'Dresser',
            'Make-up artist' => 'Make-up artist',
            'Painter on the scenery' => 'Painter on the scenery',
            'Leading energy engineer' => 'Leading energy engineer',
            'The labor protection engineer is leading' => 'The labor protection engineer is leading',
            'The head of the farm' => 'The head of the farm',
            'Warehouse manager' => 'Warehouse manager',
            'Head of the ticket office' => 'Head of the ticket office',
            'Senior Personnel Inspector' => 'Senior Personnel Inspector',
            'Senior cashier' => 'Senior cashier',
            'Senior ticket controller' => 'Senior ticket controller',
            'Ticket controller' => 'Ticket controller',
            'Wardrobe' => 'Wardrobe',
            'Senior firefighter' => 'Senior firefighter',
        ];
    }

    /**
     * Add galleryHasMedia
     *
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     * @return Employee
     */
    public function addGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    public function addGalleryHasMedia(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        return $this->addGalleryHasMedion($galleryHasMedia);
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
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

    public function getEmployeeGroup(): ?EmployeeGroup
    {
        return $this->employeeGroup;
    }

    public function setEmployeeGroup(?EmployeeGroup $employeeGroup): self
    {
        $this->employeeGroup = $employeeGroup;

        return $this;
    }

    public function getOrderPosition(): int
    {
        return $this->orderPosition;
    }

    public function setOrderPosition(int $orderPosition): self
    {
        $this->orderPosition = $orderPosition;

        return $this;
    }
}
