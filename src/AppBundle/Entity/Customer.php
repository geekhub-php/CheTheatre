<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Tests\Fixtures\Order;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 * @UniqueEntity("facebookId")
 * @ExclusionPolicy("all")
 */
class Customer implements UserInterface
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
     * @Assert\Regex(pattern="/\d/", match=false)
     * @Assert\Type("string")
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     * @Assert\Regex(pattern="/\d/", match=false)
     * @Assert\Type("string")
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     * @Expose
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     * @Assert\Email()
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     * @Expose
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100)
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true, unique=true)
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $facebookId;

    /**
     * @var ArrayCollection|Order[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\CustomerOrder",
     *     mappedBy="customer",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $orders;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return Customer
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * Add order.
     *
     * @param CustomerOrder $order
     *
     * @return Customer
     */
    public function addOrder(CustomerOrder $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order.
     *
     * @param CustomerOrder $order
     */
    public function removeOrder(CustomerOrder $order)
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
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array('ROLE_API');
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * Set facebookId.
     *
     * @param string $facebookId
     *
     * @return Customer
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId.
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return Customer
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
