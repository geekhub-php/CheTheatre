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
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $id
     *
     * @return FacebookResponse
     */
    public function setId(?string $id): FacebookResponse
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return FacebookResponse
     */
    public function setEmail(?string $email): FacebookResponse
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $firstName
     *
     * @return FacebookResponse
     */
    public function setFirstName(?string $firstName): FacebookResponse
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     *
     * @return FacebookResponse
     */
    public function setLastName(?string $lastName): FacebookResponse
    {
        $this->lastName = $lastName;

        return $this;
    }
}
