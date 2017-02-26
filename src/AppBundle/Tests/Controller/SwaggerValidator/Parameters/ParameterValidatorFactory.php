<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\PathParameter\IntegerPathParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\PathParameter\StringPathParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\QueryParameter\IntegerQueryParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\QueryParameter\StringQueryParameterValidator;

class ParameterValidatorFactory
{
    /**
     * @param string $in
     * @param string $type
     * @return IntegerPathParameterValidator|IntegerQueryParameterValidator|StringQueryParameterValidator|StringPathParameterValidator
     */
    public function getValidatorByType(string $in, string $type)
    {
        switch ($in) {
            case 'query':
                switch ($type) {
                    case 'integer':
                        return new IntegerQueryParameterValidator($this);
                    case 'string':
                        return new StringQueryParameterValidator($this);
                    default:
                        throw new \RuntimeException(sprintf('No parameter validator for "%s" type', $type));
                }
            case 'path':
                switch ($type) {
                    case 'integer':
                        return new IntegerPathParameterValidator($this);
                    case 'string':
                        return new StringPathParameterValidator($this);
                    default:
                        throw new \RuntimeException(sprintf('No parameter validator for "%s" type', $type));
                }
            default:
                throw new \RuntimeException(sprintf('No parameter validator for "%s" in', $in));
        }
    }
}
