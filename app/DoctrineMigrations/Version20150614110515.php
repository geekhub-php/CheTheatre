<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150614110515 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('history');
        $table->addColumn('type', 'string', ['length' => 255]);

        $table = $schema->getTable('performances');
        $table->addColumn('festival_id', 'integer', ['default' => NULL, 'notnull' => false]);
        $table->addForeignKeyConstraint('history', ['festival_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
