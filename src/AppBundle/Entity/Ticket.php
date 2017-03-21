<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @var array
     */
    private static $ticketStatuses = [
        self::STATUS_FREE,
        self::STATUS_BOOKED,
        self::STATUS_PAID,
    ];

    /**
     * @var Uuid
     *
     * @ORM\Column(name="id", type="uuid_binary")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=5,  nullable=false)
     * @Type("string")
     * @Expose
     */
    private $series;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20,  nullable=false)
     * @Type("string")
     * @Expose
     */
    private $number;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=false)
     * @Type("integer")
     * @Expose
     */
    private $price;

    /**
     * @var Seat
     *
     * @ORM\ManyToOne(targetEntity="Seat")
     * @ORM\JoinColumn(name="seat_id", referencedColumnName="id", nullable=false)
     *
     * @Type("Seat")
     * @Expose()
     */
    protected $seat;

    /**
     * @var PerformanceEvent
     *
     * @ORM\ManyToOne(targetEntity="PerformanceEvent")
     * @ORM\JoinColumn(name="performance_event_id", referencedColumnName="id", nullable=false)

     * @Type("PerformanceEvent")
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
     * @Assert\NotBlank()
     * @ORM\Column(name="status", type="string", columnDefinition="enum('free', 'booked', 'ordered')")
     * @Expose()
     */
    protected $status;

    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->status = self::STATUS_FREE;
    }

    /**
     * @return Uuid
     */
    public function getId()
    {
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
     * @param Seat $seat
     */
    public function setSeat($seat)
    {
        $this->seat = $seat;
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
    public function setPerformanceEvent($performanceEvent)
    {
        $this->performanceEvent = $performanceEvent;
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
        if (!in_array($status, self::$ticketStatuses)) {
            throw new \InvalidArgumentException("Invalid ticket status");
        }

        if ($this->isStatusPaid()) {
            throw new \InvalidArgumentException("Invalid status. Ticket already paid.");
        }

        if ($status === self::STATUS_BOOKED) {
            # TODO remove ticket from Customer order !!!
        }

        if ($status === self::STATUS_FREE) {
            # TODO !!!
        }

        $this->status = $status;
    }

    /**
     * @return bool
     */
    private function isStatusPaid()
    {
        return $this->status === self::STATUS_PAID;
    }
}
