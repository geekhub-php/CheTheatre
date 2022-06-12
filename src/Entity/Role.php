<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Translations\AbstractPersonalTranslatable;
use App\Traits\DeletedByTrait;
use App\Traits\TimestampableTrait;
use Gedmo\Blameable\Traits\BlameableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Table(name="roles")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\RoleTranslation")
 * @ExclusionPolicy("all")
 */
class Role extends AbstractPersonalTranslatable
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;

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
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     * @Type("string")
     * @Expose
     */
    private $description;

    /**
     * @var Performance
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Performance", inversedBy="roles")
     * @Expose()
     */
    private $performance;

    /**
     * @var Employee
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee", inversedBy="roles")
     * @Type("App\Entity\Employee")
     * @Assert\NotBlank()
     * @Expose
     */
    private $employee;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Translations\RoleTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * Unset translations
     *
     * @return Role
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
     * @return Role
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
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * @return Role
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get performance
     *
     * @return \App\Entity\Performance
     */
    public function getPerformance()
    {
        return $this->performance;
    }

    /**
     * Set performance
     *
     * @param  \App\Entity\Performance $performance
     * @return Role
     */
    public function setPerformance(\App\Entity\Performance $performance = null)
    {
        $this->performance = $performance;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \App\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set employee
     *
     * @param  \App\Entity\Employee $employee
     * @return Role
     */
    public function setEmployee(\App\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle() ?: '';
    }
}
