<?php

namespace AppBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function boot()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $platform = $em->getConnection()->getDatabasePlatform();
        if (!Type::hasType("uuid_binary")) {
            Type::addType('uuid_binary', 'Ramsey\Uuid\Doctrine\UuidBinaryType');
            $platform->registerDoctrineTypeMapping('uuid_binary', 'binary');
        }
        $platform->registerDoctrineTypeMapping('enum', 'string');
    }
}
