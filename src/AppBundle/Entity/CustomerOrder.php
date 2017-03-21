<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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

    const STATUS_PENDING  = 'pending';
    const STATUS_PAID     = 'paid';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var array
     */
    private static $customerOrderStatuses = [
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_REJECTED,
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
     * @var ArrayCollection|Ticket[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Ticket",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $tickets;

//    /**
//     * @var Customer
//     *
//     * @ORM\ManyToOne(targetEntity="Customer")
//     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
//     */
//    protected $customer;

    /**
     * @var Enum
     * @Assert\NotBlank()
     * @ORM\Column(name="status", type="string", columnDefinition="enum('free', 'booked', 'ordered')")
     * @Expose()
     */
    protected $status;

    /**
     * CustomerOrder constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return Uuid
     */
    public function getId()
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
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
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
        if (!in_array($status, self::$customerOrderStatuses)) {
            throw new \InvalidArgumentException("Invalid order status");
        }

        if ($this->status == self::STATUS_PAID) {
            throw new \InvalidArgumentException("Invalid status. Ticket already paid.");
        }

        $this->status = $status;
    }

    /**
     * Add Ticket
     *
     * @param Ticket $ticket
     * @return CustomerOrder
     */
    public function addTicket(Ticket $ticket)
    {
        if ($this->isStatusPaid()) {
            throw new \InvalidArgumentException('Order already payed. Impossible to add new ticket.');
        }
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        if ($this->isStatusPaid()) {
            throw new \InvalidArgumentException('Order already payed. Impossible to remove the ticket.');
        }
        $this->tickets->removeElement($ticket);
    }

    /**
     * @return bool
     */
    private function isStatusPaid()
    {
        return $this->status === self::STATUS_PAID;
    }
}
