<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters;


abstract class AbstractParameterValidator extends \PHPUnit_Framework_Assert
{
    /**
     * @var ParameterValidatorFactory
     */
    protected $factory;

    /**
     * @param ParameterValidatorFactory $factory
     */
    public function __construct(ParameterValidatorFactory $factory)
    {
        $this->factory = $factory;
    }
}
