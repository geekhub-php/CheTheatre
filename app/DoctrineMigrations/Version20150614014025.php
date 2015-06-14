<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150614014025 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('festival');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('dateTime', 'datetime');
        $table->addColumn('title', 'string');
        $table->addColumn('shortDescription', 'text', ['length' => 4294967295, 'default' => NULL, 'notnull' => false]);
        $table->addColumn('text', 'text', ['length' => 4294967295, 'default' => NULL, 'notnull' => false]);
        $table->addColumn('slug', 'string');
        $table->addColumn('createdAt', 'datetime');
        $table->addColumn('updatedAt', 'datetime', ['default' => NULL, 'notnull' => false]);
        $table->addColumn('deletedAt', 'datetime', ['default' => NULL, 'notnull' => false]);
        $table->addColumn('createdBy', 'string', ['default' => NULL, 'notnull' => false]);
        $table->addColumn('updatedBy', 'string', ['default' => NULL, 'notnull' => false]);
        $table->addColumn('deletedBy', 'string', ['default' => NULL, 'notnull' => false]);
        $table->addColumn('mainPicture_id', 'integer', ['default' => NULL, 'notnull' => false]);
        $table->addUniqueIndex(['mainPicture_id']);
        $table->addForeignKeyConstraint('media__media', ['mainPicture_id'], ['id']);

        $schema->getTable('performances')->addColumn('festival_id', 'integer', ['default' => NULL, 'notnull' => false]);
        $schema->getTable('performances')->addIndex(['festival_id']);
        $schema->getTable('performances')->addForeignKeyConstraint('festival', ['festival_id'], ['id']);

        $table = $schema->createTable('festival_translation');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('object_id', 'integer', ['default' => NULL, 'notnull' => false]);
        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('field', 'string', ['length' => 32]);
        $table->addColumn('content', 'text', ['length' => 4294967295, 'default' => NULL, 'notnull' => false]);
        $table->addForeignKeyConstraint('festival', ['object_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addUniqueIndex(['locale', 'object_id', 'field'], 'lookup_unique_festival_translation_idx');

        $table = $schema->createTable('festival_galleryHasMedia');
        $table->addColumn('festival_id', 'integer');
        $table->addColumn('galleryHasMedia_id', 'integer');
        $table->addIndex(['festival_id']);
        $table->addIndex(['galleryHasMedia_id']);
        $table->setPrimaryKey(['festival_id', 'galleryHasMedia_id']);
        $table->addForeignKeyConstraint('festival', ['festival_id'], ['id']);
        $table->addForeignKeyConstraint('media__gallery_media', ['galleryHasMedia_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
