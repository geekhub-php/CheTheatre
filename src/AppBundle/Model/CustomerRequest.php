<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("all")
 */
class CustomerRequest
{
    const SOCIAL_NETWORK_FACEBOOK = 'facebook';

    /**
     * @var string
     * @Type("string")
     * @Accessor(getter="getFirstName")
     * @Assert\Regex(pattern="/\d/", match=false, groups={"update"})
     * @Assert\Type("string", groups={"update"})
     * @Assert\Length(min=2, max=100, groups={"update"})
     * @Assert\NotBlank(
     *     message="not.blank", groups={"update"}
     * )
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     * @Type("string")
     * @Accessor(getter="getLastName")
     * @Assert\Regex(pattern="/\d/", match=false, groups={"update"})
     * @Assert\Type("string", groups={"update"})
     * @Assert\Length(min=2, max=100, groups={"update"})
     * @Assert\NotBlank(
     *     message="not.blank", groups={"update"}
     * )
     * @Expose
     */
    protected $lastName;

    /**
     * @var string
     * @Type("string")
     * @Accessor(getter="getEmail")
     * @Assert\Email(groups={"update"})
     * @Assert\Type("string", groups={"update"})
     * @Assert\Length(max=100, groups={"update"})
     * @Assert\NotBlank(
     *     message="not.blank", groups={"update"}
     * )
     * @Expose
     */
    protected $email;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getApiKey")
     * @Expose
     */
    protected $apiKey;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getSocialNetwork")
     * @Assert\NotBlank(groups={"socialNetwork"})
     * @Assert\Choice(callback="getSocialNetworks", groups={"socialNetwork"})
     * @Expose
     */
    protected $socialNetwork;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getSocialToken")
     * @Assert\NotBlank(groups={"socialNetwork"})
     * @Expose
     */
    protected $socialToken;

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
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
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

    /**
     * @return string
     */
    public function getSocialNetwork()
    {
        return $this->socialNetwork;
    }

    /**
     * @return array
     */
    public static function getSocialNetworks()
    {
        return [
            self::SOCIAL_NETWORK_FACEBOOK,
        ];
    }

    /**
     * @return string
     */
    public function getSocialToken()
    {
        return $this->socialToken;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return mixed
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
