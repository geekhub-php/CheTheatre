<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170622055640 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE roles SET deletedAt = '20170621182125', deletedBy='admin' WHERE deletedAt is NULL AND performance_id IN (SELECT id FROM performances WHERE deletedAt is not NULL)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
