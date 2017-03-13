<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Document\Translation;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="price_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceCategoryRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\PriceCategoryTranslation")
 * @ExclusionPolicy("all")
 */
class PriceCategory extends AbstractPersonalTranslatable implements TranslatableInterface
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
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $color;

    /**
     * @var ArrayCollection|Translation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\PriceCategoryTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var Venue
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Venue", inversedBy="priceCategory")
     * @Type("AppBundle\Entity\Venue")
     * @Expose()
     */
    protected $venue;

    /**
     * @var ArrayCollection|Seat[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Seat",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $seats;


    /**
     * PriceCategory constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->seats = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PriceCategory
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string $color
     * @return PriceCategory
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param  string $title
     * @return PriceCategory
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @param  Seat $seat
     * @return PriceCategory
     */
    public function addSeat(Seat $seat)
    {
        $this->seats[] = $seat;

        return $this;
    }

    /**
     * @param Seat $seat
     */
    public function removeSeat(Seat $seat)
    {
        $this->seats->removeElement($seat);
    }

    /**
     * @return Collection
     */
    public function getSeat()
    {
        return $this->seats;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
