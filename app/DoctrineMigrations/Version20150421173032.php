<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\StringType;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150421173032 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema
            ->getTable('performances')
            ->changeColumn('description', ['type' => StringType::getType('text'), 'length' => null]);

        $schema
            ->getTable('roles')
            ->changeColumn('description', ['type' => StringType::getType('text'), 'length' => null]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema
            ->getTable('performances')
            ->changeColumn('description', ['type' => StringType::getType('string')]);

        $schema
            ->getTable('roles')
            ->changeColumn('description', ['type' => StringType::getType('string')]);
    }
}
