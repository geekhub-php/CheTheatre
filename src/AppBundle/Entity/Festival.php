<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

class Festival extends Post
{
    /**
     * @ORM\OneToOne(targetEntity='AppBundle\Entity\FestivalPerformance')
     */
    protected $festival_performance;

    /**
     * @return mixed
     */
    public function getFestivalPerformance()
    {
        return $this->festival_performance;
    }

    /**
     * @param mixed $festival_performance
     */
    public function setFestivalPerformance($festival_performance)
    {
        $this->festival_performance = $festival_performance;
    }
}
