<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 * @ExclusionPolicy("all")
 */
class Ticket
{
    use TimestampableTrait;

    const STATUS_FREE    = 'free';
    const STATUS_BOOKED  = 'booked';
    const STATUS_PAID    = 'paid';

    /**
     * @var Uuid
     *
     * @ORM\Column(name="id", type="uuid_binary")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Type("string")
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=5,  nullable=false)
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Type("string")
     * @Expose
     */
    private $series;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20,  nullable=false)
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Type("string")
     * @Expose
     */
    private $number;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=false)
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Type("integer")
     * @Expose
     */
    private $price;

    /**
     * @var Seat
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Seat", fetch="EAGER")
     * @ORM\JoinColumn(name="seat_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Type("AppBundle\Entity\Seat")
     * @Expose()
     */
    protected $seat;

    /**
     * @var PerformanceEvent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PerformanceEvent",  fetch="EAGER")
     * @ORM\JoinColumn(name="performance_event_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("AppBundle\Entity\PerformanceEvent")
     * @Expose()
     */
    protected $performanceEvent;

    /**
     * @var CustomerOrder
     *
     * @ORM\ManyToOne(targetEntity="CustomerOrder", inversedBy="tickets")
     */
    protected $customerOrder;

    /**
     * @var Enum
     * @Assert\Choice(callback="getStatuses")
     * @ORM\Column(name="status", type="string", columnDefinition="enum('free', 'booked', 'paid')")
     * @Serializer\Groups({"get_ticket", "cget_ticket"})
     * @Expose()
     */
    protected $status;

    /**
     * Ticket constructor.
     *
     * @param Seat $seat
     * @param PerformanceEvent $performanceEvent
     */
    public function __construct(Seat $seat, PerformanceEvent $performanceEvent)
    {
        $this->id = Uuid::uuid4();
        $this->seat = $seat;
        $this->performanceEvent = $performanceEvent;
        $this->status = self::STATUS_FREE;
    }

    /**
     * @return string
     */
    public function getId()
    {
//        return $this->id->toString();
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param string $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
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
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return Seat
     */
    public function getSeat()
    {
        return $this->seat;
    }

    /**
     * @return PerformanceEvent
     */
    public function getPerformanceEvent()
    {
        return $this->performanceEvent;
    }

    /**
     * @return Enum
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Enum $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_FREE,
            self::STATUS_BOOKED,
            self::STATUS_PAID,
        ];
    }
}
