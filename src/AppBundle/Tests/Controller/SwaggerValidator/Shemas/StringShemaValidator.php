<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Shemas;

use Epfremme\Swagger\Entity\Schemas\StringSchema;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;

class StringShemaValidator extends AbstractShemaValidator implements SwaggerSchemaValidatorInterface
{
    /**
     * @param StringSchema|SchemaInterface $schema
     * @param string $actualContent
     */
    public function validate(SchemaInterface $schema, $actualContent)
    {
        self::assertInstanceOf(StringSchema::class, $schema);
        self::assertInternalType(StringSchema::STRING_TYPE, $actualContent);
    }
}
