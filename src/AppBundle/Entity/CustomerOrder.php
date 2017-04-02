<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="customer_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerOrderRepository")
 * @ExclusionPolicy("all")
 */
class CustomerOrder
{
    use TimestampableTrait;

    const STATUS_OPENED   = 'opened';
    const STATUS_CLOSED   = 'closed';
    const STATUS_PENDING  = 'pending';
    const STATUS_PAID     = 'paid';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var Uuid
     *
     * @ORM\Column(name="id", type="uuid_binary")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var ArrayCollection|Ticket[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Ticket",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $tickets;

    /**
     * @var Enum
     * @Assert\Choice(callback="getStatuses")
     * @ORM\Column(
     *     name="status",
     *     type="string",
     *     columnDefinition="enum('free', 'booked', 'ordered', 'opened', 'closed')"
     * )
     * @Expose()
     */
    protected $status;

    /**
     * CustomerOrder constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->status = self::STATUS_OPENED;
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return Ticket[]|ArrayCollection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param Ticket[]|ArrayCollection $tickets
     *
     * @return CustomerOrder
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;

        return $this;
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
     * @return CustomerOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Add Ticket
     *
     * @param Ticket $ticket
     *
     * @return CustomerOrder
     */
    public function addTicket(Ticket $ticket)
    {
        if ($this->isStatusPaid()) {
            throw new \InvalidArgumentException('Order already paid. Impossible to add new ticket.');
        }
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * @param Ticket $ticket
     *
     * @return CustomerOrder
     */
    public function removeTicket(Ticket $ticket)
    {
        if ($this->isStatusPaid()) {
            throw new \InvalidArgumentException('Order already pai. Impossible to remove the ticket.');
        }
        $this->tickets->removeElement($ticket);

        return $this;
    }

    /**
     * @return bool
     */
    private function isStatusPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPENED,
            self::STATUS_CLOSED,
            self::STATUS_PAID,
            self::STATUS_PENDING,
            self::STATUS_REJECTED,
        ];
    }
}
