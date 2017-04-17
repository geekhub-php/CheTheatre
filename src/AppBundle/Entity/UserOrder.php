<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserOrderRepository")
 * @ExclusionPolicy("all")
 */
class UserOrder
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
     * @var string
     * @Assert\Choice(callback="getStatuses")
     * @ORM\Column(name="status", type="string", length=15)
     * @Serializer\Type("string")
     * @Expose()
     */
    protected $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @Serializer\Type("AppBundle\Entity\User")
     * @Expose()
     */
    protected $user;

    /**
     * UserOrder constructor.
     */
    public function __construct(User $user)
    {
        $this->id = Uuid::uuid4();
        $this->status = self::STATUS_OPENED;
        $this->user = $user;
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
     * @return $this
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;

        return $this;
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
     * @return $this
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
     * @return $this
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
     * @return $this
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

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param $user
     *
     * @return UserOrder
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
