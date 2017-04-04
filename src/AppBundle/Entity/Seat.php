<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Document\Translation;
use JMS\Serializer\Annotation as Serializer;
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
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Type("integer")
     * @Expose()
     */
    protected $row;

    /**
     * @var integer
     * @Assert\NotBlank()
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @ORM\Column(name="place", type="integer")
     * @Type("integer")
     * @Expose()
     */
    protected $place;

    /**
     * @var VenueSector
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VenueSector", inversedBy="seats")
     * @Type("AppBundle\Entity\VenueSector")
     * @Expose()
     */
    protected $venueSector;

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
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param int $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return int
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param int $place
     */
    public function setPlace($place)
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
}
