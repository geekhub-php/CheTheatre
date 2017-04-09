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
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern     = "/^\d+(-\d+)?(,\d+(-\d+)?)*$/",
     *     htmlPattern = "^\d+(-\d+)?(,\d+(-\d+)?)*$"
     * )
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose()
     */
    protected $rows;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern     = "/^\d+(-\d+)?(,\d+(-\d+)?)*$/",
     *     htmlPattern = "^\d+(-\d+)?(,\d+(-\d+)?)*$"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Type("string")
     * @Serializer\Groups({"get_ticket"})
     * @Expose()
     */
    protected $places;
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, options={"default" : "gray"})
     * @Type("string")
     * @Expose()
     */
    protected $color;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=false)
     * @Type("integer")
     * @Expose()
     */
    protected $price;

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
     * @var PerformanceEvent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PerformanceEvent", inversedBy="priceCategories")
     * @Type("AppBundle\Entity\PerformanceEvent")
     * @Expose()
     */
    protected $performanceEvent;

    /**
     * @var VenueSector
     *
     * @Serializer\SerializedName("venueSector_id")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VenueSector")
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
     * @return PriceCategory
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * @param  string $color
     * @return PriceCategory
     */
    public function setColor($color = 'grey')
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
     * @return VenueSector
     */
    public function getVenueSector()
    {
        return $this->venueSector;
    }

    /**
     * @param VenueSector $venueSector
     */
    public function setVenueSector(VenueSector $venueSector)
    {
        $this->venueSector = $venueSector;
    }

    /**
     * @return string
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param string $rows
     */
    public function setRows(string $rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return string
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @param string $places
     */
    public function setPlaces($places)
    {
        $this->places = $places;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    /**
     * @return PerformanceEvent
     */
    public function getPerformanceEvent()
    {
        return $this->performanceEvent;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     */
    public function setPerformanceEvent(PerformanceEvent $performanceEvent)
    {
        $this->performanceEvent = $performanceEvent;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return 'PriceCategory';
    }
}
