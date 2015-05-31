<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use AppBundle\Validator\MinSizeSliderImage;

/**
 * Class FestivalPerformance
 * @package AppBundle\Entity
 * @ORM\Table(name="festival_performances")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\PerformanceTranslation")
 * @ExclusionPolicy("all")
 * @MinSizeSliderImage()
 */
class FestivalPerformance extends Performance
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\FestivalPerformanceTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(targetEntity='AppBundle\Entity\Festival')
     */
    protected $festival;

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
     * @return mixed
     */
    public function getFestival()
    {
        return $this->festival;
    }

    /**
     * @param mixed $festival
     */
    public function setFestival($festival)
    {
        $this->festival = $festival;
    }

    /**
     * @return Datetime
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param Datetime $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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
}
