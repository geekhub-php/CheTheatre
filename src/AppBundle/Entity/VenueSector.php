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
 * @ORM\Table(name="venue_sector")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenueSectorRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\VenueSectorTranslation")
 * @ExclusionPolicy("all")
 */
class VenueSector extends AbstractPersonalTranslatable implements TranslatableInterface
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
     * @var ArrayCollection|Translation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\VenueSectorTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var Venue
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Venue", inversedBy="venueSectors")
     * @Type("AppBundle\Entity\Venue")
     * @Expose()
     */
    protected $venue;

    /**
     * @var Collection|Seat[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Seat",
     *     mappedBy="venueSector",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $seats;

    /**
     * VenueSector constructor.
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
     * @return VenueSector
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
     * @param  string $title
     * @return VenueSector
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
     * @return VenueSector
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