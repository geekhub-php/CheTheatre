<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170403193327 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A39395C3F3');
        $this->addSql('DROP TABLE Customer');
        $this->addSql('DROP TABLE customer_translation');
        $this->addSql('DROP INDEX IDX_3B1CE6A39395C3F3 ON customer_order');
        $this->addSql('ALTER TABLE customer_order DROP customer_id, CHANGE status status enum(\'free\', \'booked\', \'ordered\', \'opened\', \'closed\')');
        $this->addSql('ALTER TABLE ticket CHANGE status status enum(\'free\', \'booked\', \'paid\', \'offline\')');
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21CC32B8D0');
        $this->addSql('DROP INDEX UNIQ_62534E21CC32B8D0 ON customers');
        $this->addSql('ALTER TABLE customers DROP createdAt, DROP title, DROP shortDescription, DROP text, DROP slug, DROP updatedAt, DROP deletedAt, DROP createdBy, DROP updatedBy, DROP deletedBy, DROP mainPicture_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Customer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, last_name VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, email VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, username VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, api_key VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, facebook_id VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_784FEC5F9BE8FD98 (facebook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci, field VARCHAR(32) NOT NULL COLLATE utf8_unicode_ci, content LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX lookup_unique_customer_translation_idx (locale, object_id, field), INDEX IDX_B7226E67232D562B (object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_translation ADD CONSTRAINT FK_B7226E67232D562B FOREIGN KEY (object_id) REFERENCES customers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_order ADD customer_id INT DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A39395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id)');
        $this->addSql('CREATE INDEX IDX_3B1CE6A39395C3F3 ON customer_order (customer_id)');
        $this->addSql('ALTER TABLE customers ADD createdAt DATETIME NOT NULL, ADD title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD shortDescription LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD slug VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD updatedAt DATETIME DEFAULT NULL, ADD deletedAt DATETIME DEFAULT NULL, ADD createdBy VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD updatedBy VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD deletedBy VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD mainPicture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21CC32B8D0 FOREIGN KEY (mainPicture_id) REFERENCES media__media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E21CC32B8D0 ON customers (mainPicture_id)');
        $this->addSql('ALTER TABLE ticket CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
