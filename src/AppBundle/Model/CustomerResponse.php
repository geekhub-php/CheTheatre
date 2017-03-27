<?php

namespace AppBundle\Model;

use AppBundle\Entity\Customer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("all")
 */
class CustomerResponse
{
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
}
