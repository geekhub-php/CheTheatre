<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class DTOCustomer
{
    /**
     * @var string
     * @Assert\Type("string")
     */
    public $apiKeyToken;
    /**
     * @var string
     * @Assert\Type("string")
     */
    public $firstName;
    /**
     * @var string
     * @Assert\Type("string")
     */
    public $lastName;
    /**
    * @var string
    * @Assert\Type("string")
    * @Assert\Email()
    */

    public $email;
    /**
     * @var string
     * @Assert\Type("string")
     */
    public $socialNetwork;
    /**
     * @var string
     * @Assert\Type("string")
     */
    public $socialToken;

    public function setSocialToken($socialToken)
    {
        $this->socialToken = $socialToken;

        return $this;
    }


    public function getSocialToken()
    {
        return $this->socialToken;
    }

    public function setSocialNetwork($socialNetwork)
    {
        $this->socialNetwork = $socialNetwork;

        return $this;
    }


    public function getSocialNetwork()
    {
        return $this->socialNetwork;
    }

    public function setApiKeyToken($apiKeyToken)
    {
        $this->apiKeyToken = $apiKeyToken;

        return $this;
    }


    public function getApiKeyToken()
    {
        return $this->apiKeyToken;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }


    public function getFirstname()
    {
        return $this->firstName;
    }


    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }


    public function getLastName()
    {
        return $this->lastName;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }


    public function getEmail()
    {
        return $this->email;
    }
}
