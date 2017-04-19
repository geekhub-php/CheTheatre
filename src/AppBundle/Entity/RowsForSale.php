<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Document\Translation;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;

/**
 * @ORM\Table(name="rows_for_sale")
 * @UniqueEntity(fields={"row","venueSector"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RowsForSaleRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\RowsForSaleTranslation")
 * @ExclusionPolicy("all")
 */
class RowsForSale extends AbstractPersonalTranslatable implements TranslatableInterface
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
     * @Serializer\Groups({"get_ticket"})
     * @Type("integer")
     * @Expose()
     */
    protected $row;

    /**
     * @var VenueSector
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VenueSector")
     */
    protected $venueSector;

    /**
     * @Serializer\SerializedName("venueSectorId")
     * @Serializer\Accessor("getVenueSectorId")
     * @Type("integer")
     * @Expose()
     */
    protected $venueSectorId;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\PerformanceEvent",
     *     mappedBy="rowsForSale",
     *     cascade={"persist","detach","merge"}
     * )
     */
    protected $performanceEvent;

    /**
     * @var ArrayCollection|Translation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\RowsForSaleTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @return Collection
     */
    public function getPerformanceEvent()
    {
        return $this->performanceEvent;
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
    public function setVenueSector($venueSector)
    {
        $this->venueSector = $venueSector;
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
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getVenueSector()->getTitle().' '.$this->getRow().' ряд';
    }

    /**
     * @return int
     */
    public function getVenueSectorId()
    {
        return $this->getVenueSector()->getId();
    }
}
