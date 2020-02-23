<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\StringType;
use Doctrine\Migrations\AbstractMigration;

class Version20150531150000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $schema->getTable('employees')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('employees')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('employees')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);

        $schema->getTable('history')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('history')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('history')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);

        $schema->getTable('performances')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('performances')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('performances')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);

        $schema->getTable('performance_schedule')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('performance_schedule')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('performance_schedule')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);

        $schema->getTable('posts')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('posts')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('posts')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);

        $schema->getTable('roles')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('roles')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('roles')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);

        $schema->getTable('tags')->addColumn('createdBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('tags')->addColumn('updatedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
        $schema->getTable('tags')->addColumn('deletedBy', 'string', ['length' => 255, 'default' => NULL, 'notnull' => false]);
    }

    public function down(Schema $schema) : void
    {
    }
}
