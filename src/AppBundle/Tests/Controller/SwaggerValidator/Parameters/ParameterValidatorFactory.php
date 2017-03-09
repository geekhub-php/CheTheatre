<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\HeaderParameter\StringHeaderParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\PathParameter\IntegerPathParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\PathParameter\StringPathParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\QueryParameter\IntegerQueryParameterValidator;
use AppBundle\Tests\Controller\SwaggerValidator\Parameters\QueryParameter\StringQueryParameterValidator;

class ParameterValidatorFactory
{
    /**
     * @param string $in
     * @param string $type
     * @return StringHeaderParameterValidator|IntegerPathParameterValidator|IntegerQueryParameterValidator|StringQueryParameterValidator|StringPathParameterValidator
     */
    public function getValidatorByType(string $in, string $type)
    {
        switch ($in) {
            case 'header':
                sprintf('Validator for type in "%s"', $in);
                switch ($type) {
                    case 'string':
                        return new StringHeaderParameterValidator($this);
                    default:
                        throw new \RuntimeException(sprintf('No parameter validator for "%s" type', $type));
                }
                break;
            case 'query':
                sprintf('Validator for type in "%s"', $in);
                switch ($type) {
                    case 'integer':
                        return new IntegerQueryParameterValidator($this);
                    case 'string':
                        return new StringQueryParameterValidator($this);
                    default:
                        throw new \RuntimeException(sprintf('No parameter validator for "%s" type', $type));
                }
                break;
            case 'path':
                sprintf('Validator for type in "%s"', $in);
                switch ($type) {
                    case 'integer':
                        return new IntegerPathParameterValidator($this);
                    case 'string':
                        return new StringPathParameterValidator($this);
                    default:
                        throw new \RuntimeException(sprintf('No parameter validator for "%s" type', $type));
                }
                break;
            default:
                throw new \RuntimeException(sprintf('No parameter validator for "%s" in', $in));
        }
    }
}
