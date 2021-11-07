<?php

namespace App\Entity;

use App\Repository\EmployeeGroupRepository;
use App\Traits\DeletedByTrait;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;

/**
 * @ORM\Table(name="employees_group")
 * @ORM\Entity(repositoryClass=EmployeeGroupRepository::class)
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\EmployeeGroupTranslation")
 * @Serializer\ExclusionPolicy("all")
 */
class EmployeeGroup extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity, PersonalTranslatableTrait;
    public const EPOCH_ID = 9;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose
     */
    private string $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    private string $slug;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     * @Serializer\Expose
     */
    private int $position;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Translations\EmployeeGroupTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\OneToMany(targetEntity=Employee::class, mappedBy="employeeGroup")
     */
    private Collection $employees;

    public function __construct()
    {
        parent::__construct();
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position)
    {
        $this->position = $position;
        return $this;
    }

    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setEmployeeGroup($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getEmployeeGroup() === $this) {
                $employee->setEmployeeGroup(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
