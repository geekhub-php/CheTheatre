<?php

namespace AppBundle\Model;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
//use AppBundle\Entity\Customer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("all")
 */
class UpdateCustomerRequest
{


    /**
     * @var string
     *  @Type("string")
     * @Accessor(getter="getFirstName")
     * @Assert\Regex(pattern="/\d/", match=false)
     * @Assert\Type("string")
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank(
     *     message="not.blank"
     * )
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     * @Type("string")
     * @Accessor(getter="getLastName")
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
     * @Type("string")
     * @Accessor(getter="getEmail")
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

