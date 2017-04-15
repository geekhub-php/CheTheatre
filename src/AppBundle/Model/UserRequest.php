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
class UserRequest
{
    const SOCIAL_NETWORK_FACEBOOK = 'facebook';

    /**
     * @var string
     *
     * @Assert\Regex(pattern="/\d/", match=false, groups={"update"})
     * @Assert\Type("string", groups={"update"})
     * @Assert\Length(min=2, max=100, groups={"update"})
     * @Assert\NotBlank(
     *     message="not.blank", groups={"update"}
     * )
     *
     * @Type("string")
     * @Accessor(getter="getFirstName")
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="/\d/", match=false, groups={"update"})
     * @Assert\Type("string", groups={"update"})
     * @Assert\Length(min=2, max=100, groups={"update"})
     * @Assert\NotBlank(
     *     message="not.blank", groups={"update"}
     * )
     *
     * @Type("string")
     * @Accessor(getter="getLastName")
     * @Expose
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\Email(groups={"update"})
     * @Assert\Type("string", groups={"update"})
     * @Assert\Length(max=100, groups={"update"})
     * @Assert\NotBlank(
     *     message="not.blank", groups={"update"}
     * )
     *
     * @Type("string")
     * @Accessor(getter="getEmail")
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
     * @Assert\NotBlank(groups={"socialNetwork"})
     * @Assert\Choice(callback="getSocialNetworks", groups={"socialNetwork"})
     *
     * @Type("string")
     * @Accessor(getter="getSocialNetwork")
     * @Expose
     */
    protected $socialNetwork;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"socialNetwork"})
     *
     * @Type("string")
     * @Accessor(getter="getSocialToken")
     * @Expose
     */
    protected $socialToken;

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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getSocialNetwork(): string
    {
        return $this->socialNetwork;
    }

    /**
     * @return array
     */
    public static function getSocialNetworks(): ?array
    {
        return [
            self::SOCIAL_NETWORK_FACEBOOK,
        ];
    }

    /**
     * @return string
     */
    public function getSocialToken(): ?string
    {
        return $this->socialToken;
    }

    /**
     * @param string $apiKey
     *
     * @return UserRequest
     */
    public function setApiKey(?string $apiKey): UserRequest
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
}
