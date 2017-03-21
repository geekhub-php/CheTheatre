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
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="seat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeatRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\SeatTranslation")
 * @ExclusionPolicy("all")
 */
class Seat extends AbstractPersonalTranslatable implements TranslatableInterface
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
     * @Type("AppBundle\Entity\VenueSector")
     * @Expose()
     */
    protected $venueSector;

    /**
     * @var PriceCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PriceCategory", inversedBy="seats")
     * @Type("AppBundle\Entity\PriceCategory")
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
     * @var ArrayCollection|Translation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\SeatTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

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
