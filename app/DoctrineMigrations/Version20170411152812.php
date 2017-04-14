<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170411152812 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA31C7C4AAD');
        $this->addSql('DROP INDEX IDX_97A0ADA31C7C4AAD ON ticket');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_62534e219be8fd98 TO UNIQ_1483A5E99BE8FD98');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket CHANGE user_order_id user_order_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\'');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA31C7C4AAD FOREIGN KEY (user_order_id) REFERENCES user_order (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA31C7C4AAD ON ticket (user_order_id)');
    }
}
