<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Type;

class FacebookResponse
{
    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getId")
     */
    protected $id;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getEmail")
     */
    protected $email;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getFirstName")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getLastName")
     */
    protected $lastName;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
}
