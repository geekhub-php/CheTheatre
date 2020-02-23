<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20150614010455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $schema->getTable('history')->addColumn('shortDescription', 'string', ['length' => 4294967295, 'default' => NULL, 'notnull' => false]);
    }

    public function down(Schema $schema) : void
    {
    }
}
