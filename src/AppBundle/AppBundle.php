<?php

namespace AppBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function boot()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        if (!Type::hasType("uuid_binary")) {
            Type::addType('uuid_binary', 'Ramsey\Uuid\Doctrine\UuidBinaryType');
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('uuid_binary', 'binary');
        }

//        if (!Type::hasType('enum')) {
//            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
//        }
    }
}
