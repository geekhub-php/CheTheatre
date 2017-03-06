<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Shemas;

use Epfremme\Swagger\Entity\Schemas\ArraySchema;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;

class ArrayShemaValidator extends AbstractShemaValidator implements SwaggerSchemaValidatorInterface
{
    /**
     * @param ArraySchema|SchemaInterface $schema
     * @param array $actualContent
     */
    public function validate(SchemaInterface $schema, $actualContent)
    {
        self::assertNotNull($actualContent);
        self::assertInstanceOf(ArraySchema::class, $schema);
        self::assertInternalType(ArraySchema::ARRAY_TYPE, $actualContent);
    }
}
