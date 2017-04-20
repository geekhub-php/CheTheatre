<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
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
    private $seat;

    /**
     * @var PerformanceEvent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PerformanceEvent",  fetch="EAGER")
     * @ORM\JoinColumn(name="performance_event_id", referencedColumnName="id", nullable=false)
     * @Type("AppBundle\Entity\PerformanceEvent")
     */
    private $performanceEvent;

    /**
     * @var UserOrder
     *
     * @ORM\ManyToOne(targetEntity="UserOrder", inversedBy="tickets")
     * @ORM\Column(name="user_order_id", type="integer", nullable=true)
     */
    protected $userOrder;

    /**
     * @var PriceCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PriceCategory", fetch="EAGER")
     * @ORM\JoinColumn(name="price_category_id", referencedColumnName="id", nullable=false)
     *
     * @Type("AppBundle\Entity\PriceCategory")
     * @Expose()
     */
    private $priceCategory;

    /**
     * @var string
     * @Assert\Choice(callback="getStatuses")
     * @ORM\Column(name="status", type="string", length=15)
     * @Serializer\Groups({"get_ticket"})
     * @Type("string")
     * @Expose()
     */
    private $status;

    /**
     * Ticket constructor.
     *
     * @param Seat $seat
     * @param PerformanceEvent $performanceEvent
     * @param PriceCategory $priceCategory
     * @param int $ticketPrice
     * @param \DateTime $seriesDate
     * @param string $seriesNumber
     * @param $status string
     */
    public function __construct(
        Seat $seat,
        PerformanceEvent $performanceEvent,
        PriceCategory $priceCategory,
        int $ticketPrice,
        \DateTime $seriesDate,
        string $seriesNumber,
        $status = self::STATUS_FREE
    ) {
        $this->id = Uuid::uuid4();
        $this->seat = $seat;
        $this->performanceEvent = $performanceEvent;
        $this->priceCategory = $priceCategory;
        $this->price = $ticketPrice;
        $this->seriesDate = $seriesDate;
        $this->seriesNumber = $seriesNumber;
        $this->status = $status;
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
        return empty($this->priceCategory) ? 0 : $this->priceCategory->getId();
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
     * @return PriceCategory
     */
    public function getPriceCategory(): PriceCategory
    {
        return $this->priceCategory;
    }

    /**
     * @return bool
     */
    public function isRemovable()
    {
        return $this->getStatus() != self::STATUS_PAID && $this->getStatus() != self::STATUS_BOOKED;
    }
}
