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
            ->getTable('employees')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('festivals')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('festival_performances')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('history')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('performances')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('performance_schedule')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('posts')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('roles')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('tags')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('media__gallery')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('media__gallery_media')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);

        $schema
            ->getTable('media__media')
            ->addColumn('createdBy', 'string', ['length' => 255])
            ->addColumn('updatedBy', 'string', ['length' => 255]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
