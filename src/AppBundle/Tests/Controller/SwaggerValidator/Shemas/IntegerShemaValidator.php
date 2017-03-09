<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Shemas;

use Epfremme\Swagger\Entity\Schemas\IntegerSchema;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;

class IntegerShemaValidator extends AbstractShemaValidator implements SwaggerSchemaValidatorInterface
{
    /**
     * @param IntegerSchema|SchemaInterface $schema
     * @param integer $actualContent
     */
    public function validate(SchemaInterface $schema, $actualContent)
    {
        self::assertInstanceOf(IntegerSchema::class, $schema);
        self::assertInternalType(IntegerSchema::INTEGER_TYPE, $actualContent);
    }
}
