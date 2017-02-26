<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Shemas;

use Epfremme\Swagger\Entity\Schemas\ObjectSchema;
use Epfremme\Swagger\Entity\Schemas\RefSchema;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;

class RefShemaValidator extends AbstractShemaValidator implements SwaggerSchemaValidatorInterface
{
    /**
     * @param RefSchema|SchemaInterface $schema
     * @param object $actualContent
     */
    public function validate(SchemaInterface $schema, $actualContent)
    {
        self::assertInstanceOf(RefSchema::class, $schema);
        self::assertInternalType(ObjectSchema::OBJECT_TYPE, $actualContent);
    }
}
