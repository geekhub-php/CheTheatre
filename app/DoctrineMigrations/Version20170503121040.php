<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170503121040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Client (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(20) NOT NULL, countAttempts INT NOT NULL, banned TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C0E80163A5E3B32D (ip), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Swindler (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, countAttempts INT NOT NULL, banned TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_34C3EDE3A5E3B32D (ip), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE Client');
    }
}
