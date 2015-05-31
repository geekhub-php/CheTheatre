<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

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
     * @var \Datetime
     *
     * @Expose
     * @Type("string")
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string", nullable=true)
     */
    private $createdBy;

    /**
     * @var \Datetime
     *
     * @Expose
     * @Type("string")
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string", nullable=true)
     */
    private $updatedBy;

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
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \Datetime
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param \Datetime $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }
}
