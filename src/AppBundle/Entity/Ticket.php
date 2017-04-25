<?php

namespace AppBundle\Entity;

use AppBundle\Exception\TicketStatusConflictException;
use AppBundle\Traits\TimestampableTrait;
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
     * @ORM\Column(name="series_date", type="datetime", nullable=false)
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("DateTime")
     * @Expose
     */
    private $seriesDate;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="series_number", type="string", length=10, nullable=false)
     *
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose
     */
    private $seriesNumber;

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
     * @var UserOrder
     *
     * @ORM\ManyToOne(targetEntity="UserOrder", inversedBy="tickets", fetch="EAGER")
     * @ORM\JoinColumn(name="user_order_id", referencedColumnName="id", nullable=true)
     */
    protected $userOrder;

    /**
     * @var string
     * @Assert\Choice(callback="getStatuses")
     * @ORM\Column(name="status", type="string", length=15)
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose()
     */
    protected $status;

    /**
     * Ticket constructor.
     *
     * @param Seat $seat
     * @param PerformanceEvent $performanceEvent
     * @param int $ticketPrice
     * @param \DateTime $seriesDate
     * @param string $seriesNumber
     */
    public function __construct(
        Seat $seat,
        PerformanceEvent $performanceEvent,
        int $ticketPrice,
        \DateTime $seriesDate,
        string $seriesNumber
    ) {
        $this->id = Uuid::uuid4();
        $this->status = self::STATUS_FREE;
        $this->seat = $seat;
        $this->performanceEvent = $performanceEvent;
        $this->price = $ticketPrice;
        $this->seriesDate = $seriesDate;
        $this->seriesNumber = $seriesNumber;
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param String $status
     *
     * @return Ticket
     */
    public function setStatus($status)
    {
        if (!in_array($status, $this->getStatuses())) {
            throw new TicketStatusConflictException('Invalid status');
        }

        if ($this->getStatus() === Ticket::STATUS_PAID) {
            throw new TicketStatusConflictException("Invalid status. Ticket already paid.");
        }

        if ($this->getStatus() === $status) {
            return $this;
        }

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
    public function getSeriesDate(): \DateTime
    {
        return $this->seriesDate;
    }

    /**
     * @return string
     */
    public function getSeriesNumber(): string
    {
        return $this->seriesNumber;
    }

    /**
     * Set userOrder
     *
     * @param UserOrder $userOrder
     *
     * @return Ticket
     */
    public function setUserOrder(UserOrder $userOrder)
    {
        if ($this->getStatus() !== Ticket::STATUS_FREE) {
            throw new TicketStatusConflictException('Ticket is not free');
        }
        $this->setStatus(self::STATUS_BOOKED);

        $this->userOrder = $userOrder;

        return $this;
    }

    /**
     * Remove userOrder from ticket
     *
     * @return $this
     */
    public function removeUserOrder()
    {
        $this->userOrder = null;
        $this->status = self::STATUS_FREE;

        return $this;
    }

    /**
     * Get userOrder
     *
     * @return UserOrder
     */
    public function getUserOrder(): ?UserOrder
    {
        return $this->userOrder;
    }
}
