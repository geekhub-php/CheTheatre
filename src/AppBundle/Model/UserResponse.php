<?php

namespace AppBundle\Model;

use AppBundle\Entity\User;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ExclusionPolicy("all")
 */
class UserResponse
{
    /**
     * @var User
     *
     * @Type("AppBundle\Entity\User")
     * @Expose
     */
    protected $user;

    /**
     * @var string
     *
     * @Type("string")
     * @Accessor(getter="getApiKey")
     * @Expose
     */
    protected $apiKey;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserResponse
     */
    public function setUser(User $user): UserResponse
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->getUser()->getApiKey();
    }
}
