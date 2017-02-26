<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Shemas;

class SchemaValidatorFactory
{
    /**
     * @param string $type
     * @return SwaggerSchemaValidatorInterface
     */
    public function getValidatorByType(string $type)
    {
        switch ($type) {
            case 'object':
                return new ObjectSchemaValidator($this);
            case 'integer':
                return new IntegerShemaValidator($this);
            case 'string':
                return new StringShemaValidator($this);
            case 'ref':
                return new RefShemaValidator($this);
            case 'array':
                return new ArrayShemaValidator($this);
            default:
                throw new \RuntimeException(sprintf('No shema validator for "%s" type', $type));
        }
    }
}
