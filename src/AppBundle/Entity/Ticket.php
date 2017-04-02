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
    const STATUS_OFFLINE = 'offline';

    /**
     * @var Uuid
     *
     * @ORM\Column(name="id", type="uuid_binary")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("DateTime")
     * @Expose
     */
    private $setDate;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=10,  nullable=false)
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose
     */
    private $setNumber;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=false)
     *
     * @Serializer\Groups({"get_ticket"})
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
     * @Serializer\Groups({"get_ticket"})
     * @Type("AppBundle\Entity\Seat")
     * @Expose()
     */
    protected $seat;

    /**
     * @var PerformanceEvent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PerformanceEvent",  fetch="EAGER")
     * @ORM\JoinColumn(name="performance_event_id", referencedColumnName="id", nullable=false)
     * @Type("AppBundle\Entity\PerformanceEvent")
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
     * @ORM\Column(name="status", type="string", columnDefinition="enum('free', 'booked', 'paid', 'offline')")
     * @Serializer\Groups({"get_ticket"})
     * @Expose()
     */
    protected $status;

    /**
     * Ticket constructor.
     *
     * @param Seat $seat
     * @param PerformanceEvent $performanceEvent
     * @param int $ticketPrice
     * @param \DateTime $setDate
     * @param string $setNumber
     */
    public function __construct(
        Seat $seat,
        PerformanceEvent $performanceEvent,
        int $ticketPrice,
        \DateTime $setDate,
        string $setNumber
    ) {
        $this->id = Uuid::uuid4();
        $this->status = self::STATUS_FREE;
        $this->seat = $seat;
        $this->performanceEvent = $performanceEvent;
        $this->price = $ticketPrice;
        $this->setDate = $setDate;
        $this->setNumber = $setNumber;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return Seat
     */
    public function getSeat(): Seat
    {
        return $this->seat;
    }

    /**
     * @return PerformanceEvent
     */
    public function getPerformanceEvent(): PerformanceEvent
    {
        return $this->performanceEvent;
    }

    /**
     * Get PerformanceEvent Id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("performance_event_id")
     * @Type("integer")
     * @Serializer\Groups({"get_ticket"})
     *
     * @return integer
     */
    public function getPerformanceEventId(): int
    {
        return $this->performanceEvent->getId();
    }

    /**
     * Get PriceCategory Id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("price_category_id")
     * @Type("integer")
     * @Serializer\Groups({"get_ticket"})
     *
     * @return integer
     */
    public function getPriceCategoryId(): int
    {
        //TODO
        return 0;
    }

    /**
     * @return Enum
     */
    public function getStatus(): Enum
    {
        return $this->status;
    }

    /**
     * @param Enum|String $status
     *
     * @return Ticket
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_FREE,
            self::STATUS_BOOKED,
            self::STATUS_PAID,
            self::STATUS_OFFLINE,
        ];
    }

    /**
     * @return \DateTime
     */
    public function getSetDate(): \DateTime
    {
        return $this->setDate;
    }

    /**
     * @return string
     */
    public function getSetNumber(): string
    {
        return $this->setNumber;
    }
}
