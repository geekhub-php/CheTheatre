<?php

namespace AppBundle\Tests\Controller\SwaggerValidator;

use Epfremme\Swagger\Entity\Schemas\ObjectSchema;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;

class ObjectSchemaValidator extends AbstractShemaValidator implements SwaggerSchemaValidatorInterface
{
    /**
     * @param ObjectSchema|SchemaInterface $schema
     * @param object $actualContent
     */
    public function validate(SchemaInterface $schema, $actualContent)
    {
        self::assertInstanceOf(ObjectSchema::class, $schema);
        self::assertInternalType('object', $actualContent);

        /**
         * @var string $propertyName
         * @var SchemaInterface $property
         */
        foreach ($schema->getProperties() as $propertyName => $property) {
            self::assertObjectHasAttribute($propertyName, $actualContent);
            $validator = $this->factory->getValidatorByType($property->getType());
            $validator->validate($property, $actualContent->$propertyName);
        }
    }
}
