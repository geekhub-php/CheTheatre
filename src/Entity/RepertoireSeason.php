<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepertoireSeasonRepository")
 * @JMS\ExclusionPolicy("all")
 */
class RepertoireSeason
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @JMS\Expose()
     */
    private $number;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Performance", mappedBy="seasons", fetch="EAGER")
     */
    private $performances;

    /**
     * @JMS\Expose()
     */
    public $performanceCount;

    public function __construct()
    {
        $this->performances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Performance[]
     */
    public function getPerformances(): Collection
    {
        return $this->performances;
    }

    public function setPerformances(iterable $performances)
    {
        if (is_array($performances)) $this->performances = new ArrayCollection($performances);
        elseif ($performances instanceof Collection) $this->performances = $performances;
        else throw new \InvalidArgumentException('Argument must be array or Collection');
    }

    public function addPerformance(Performance $performance): self
    {
        if (!$this->performances->contains($performance)) {
            $this->performances[] = $performance;
            $performance->addSeason($this);
        }

        return $this;
    }

    public function removePerformance(Performance $performance): self
    {
        if ($this->performances->contains($performance)) {
            $this->performances->removeElement($performance);
            $performance->removeSeason($this);
        }

        return $this;
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("name")
     */
    public function __toString()
    {
        if (!$this->startDate || !$this->endDate) return $this->getNumber();
        return sprintf(
            '%s (%s - %s)',
            $this->getNumber(),
            $this->getStartDate()->format('Y'),
            $this->getEndDate()->format('Y')
        );
    }
}
