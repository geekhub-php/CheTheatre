<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Festival
 * @package AppBundle\Entity
 * @ORM\Table(name="festivals")
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\FestivalTranslation")
 * @ExclusionPolicy("all")
 */
class Festival extends Post
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\FestivalTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\OneToMany(targetEntity='AppBundle\Entity\FestivalPerformance')
     */
    protected $festivalPerformances;

    /**
     * @return mixed
     */
    public function getFestivalPerformances()
    {
        return $this->festivalPerformances;
    }

    /**
     * @param FestivalPerformance $festivalPerformance
     * @return $this
     */
    public function addFestivalPerformance(\AppBundle\Entity\FestivalPerformance $festivalPerformance)
    {
        $this->festivalPerformances[] = $festivalPerformance;

        return $this;
    }

    /**
     * @param FestivalPerformance $festivalPerformance
     * @return $this
     */
    public function removeFestivalPerformance(\AppBundle\Entity\FestivalPerformance $festivalPerformance)
    {
        $this->festivalPerformances->removeElement($festivalPerformance);

        return $this;
    }
}
