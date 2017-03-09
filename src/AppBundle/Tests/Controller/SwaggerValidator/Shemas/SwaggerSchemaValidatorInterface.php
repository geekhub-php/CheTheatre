<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Shemas;

use Epfremme\Swagger\Entity\Schemas\SchemaInterface;

interface SwaggerSchemaValidatorInterface
{
    public function validate(SchemaInterface $schema, $actualContent);
}
