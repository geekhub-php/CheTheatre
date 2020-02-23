<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160503093009 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts ADD pinned TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts DROP pinned');
    }
}
