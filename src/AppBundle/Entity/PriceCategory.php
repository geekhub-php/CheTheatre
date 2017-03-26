<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $rows;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $places;
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose()
     */
    protected $color;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VenueSector", inversedBy="priceCategories")
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
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return VenueSector
     */
    public function getVenueSector(): VenueSector
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
}
