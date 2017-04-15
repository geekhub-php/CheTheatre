<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Tests\Fixtures\Order;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @DoctrineAssert\UniqueEntity(
 *     fields="facebookId",
 *     message="facebookId already exists",
 *     groups={"uniqFacebookId"}
 * )
 * @DoctrineAssert\UniqueEntity(
 *     fields="apiKey",
 *     message="apikey already exists",
 *     groups={"uniqApikey"}
 * )
 *
 * @ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     *
     * @Assert\Regex(pattern="/\d/", match=false)
     * @Assert\Type("string")
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     *
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     *
     * @Assert\Regex(pattern="/\d/", match=false)
     * @Assert\Type("string")
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     *
     * @Expose
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     *
     * @Assert\Email()
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     *
     * @Expose
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255, nullable=true, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $facebookId;

    /**
     * @var ArrayCollection|Order[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\UserOrder",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $orders;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50)
     */
    private $role;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $apiKey
     *
     * @return User
     */
    public function setApiKey(?string $apiKey): User
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Add order.
     *
     * @param UserOrder $order
     *
     * @return User
     */
    public function addOrder(UserOrder $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order.
     *
     * @param UserOrder $order
     */
    public function removeOrder(UserOrder $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole(?string $role): User
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }



    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId(?string $facebookId): User
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(?string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
