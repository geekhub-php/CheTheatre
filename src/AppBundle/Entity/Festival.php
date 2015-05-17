<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Festival
 * @package AppBundle\Entity
 */
class Festival extends Post
{
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
