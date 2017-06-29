<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use AppBundle\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Traits\DeletedByTrait;

/**
 * @ORM\Table(name="user_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserOrderRepository")
 * @ExclusionPolicy("all")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class UserOrder
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;

    const STATUS_OPENED   = 'opened';
    const STATUS_CLOSED   = 'closed';
    const STATUS_PENDING  = 'pending';
    const STATUS_PAID     = 'paid';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var ArrayCollection|Ticket[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Ticket",
     *     mappedBy="userOrder",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="orders")
     *
     * @Type("AppBundle\Entity\User")
     */
    private $user;
    /**
     * UserOrder constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->status = self::STATUS_OPENED;
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
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

    public function __toString()
    {
        return (string) $this->getId();
    }
}
