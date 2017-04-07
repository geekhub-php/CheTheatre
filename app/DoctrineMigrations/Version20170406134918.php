<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170406134918 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_order (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', customer_id INT DEFAULT NULL, status VARCHAR(15) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, INDEX IDX_3B1CE6A39395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, username VARCHAR(100) NOT NULL, api_key VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_62534E219BE8FD98 (facebook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A39395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A39395C3F3');
        $this->addSql('DROP TABLE customer_order');
        $this->addSql('DROP TABLE customers');
    }
}
