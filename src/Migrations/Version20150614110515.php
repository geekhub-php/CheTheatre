<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20150614110515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('history');
        $table->addColumn('type', 'string', ['length' => 255]);

        $table = $schema->getTable('performances');
        $table->addColumn('festival_id', 'integer', ['default' => NULL, 'notnull' => false]);
        $table->addForeignKeyConstraint('history', ['festival_id'], ['id']);
    }

    public function down(Schema $schema) : void
    {
    }
}
