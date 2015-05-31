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
}
