<?php

namespace AppBundle\Tests\Controller\SwaggerValidator;

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
            default:
                throw new \RuntimeException(sprintf('No validator for "%s" type', $type));
        }
    }
}
