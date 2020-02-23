<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150917142245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE performance_schedule ADD venue VARCHAR(255) NOT NULL');
        $this->addSql("UPDATE performance_schedule SET venue='venue-theatre' WHERE datetime < date('2015-07-01')");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE performance_schedule DROP venue');
    }
}
