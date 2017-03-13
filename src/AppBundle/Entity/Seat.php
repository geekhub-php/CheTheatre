<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Document\Translation;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="seat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeatRepository")
 * @ExclusionPolicy("all")
 */
class Seat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $uuid;

    /**
     * @var integer
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="row", type="integer")
     * @Type("integer")
     * @Expose()
     */
    protected $row;

    /**
     * @var integer
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="place", type="integer")
     * @Type("integer")
     * @Expose()
     */
    protected $place;

    /**
     * @var VenueSector
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VenueSector", inversedBy="seats")
     * @Type("AppBundle\Entity\Venue")
     * @Expose()
     */
    protected $venueSector;

    /**
     * @var PriceCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PriceCategory", inversedBy="seats")
     * @Type("AppBundle\Entity\Venue")
     * @Expose()
     */
    protected $priceCategory;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Seat
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @param int $row
     */
    public function setRow(int $row)
    {
        $this->row = $row;
    }

    /**
     * @return int
     */
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param int $place
     */
    public function setPlace(int $place)
    {
        $this->place = $place;
    }

    /**
     * @return VenueSector
     */
    public function getVenueSector(): VenueSector
    {
        return $this->venueSector;
    }

    /**
     * @return PriceCategory
     */
    public function getPriceCategory(): PriceCategory
    {
        return $this->priceCategory;
    }
}
