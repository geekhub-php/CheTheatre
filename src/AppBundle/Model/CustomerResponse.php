<?php

namespace AppBundle\Model;

use AppBundle\Entity\Customer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ExclusionPolicy("all")
 */
class CustomerResponse
{
    const SOCIAL_NETWORK_FACEBOOK= 'facebook';

    /**
     * @var Customer
     *
     * @Type("AppBundle\Entity\Customer")
     * @Expose
     */
    protected $customer;

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
     * @param Customer $customer
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param  Customer $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getCustomer()->getApiKey();
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
}
