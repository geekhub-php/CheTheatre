<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170415085454 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket CHANGE user_order_id user_order_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\'');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36D128938 FOREIGN KEY (user_order_id) REFERENCES user_order (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA36D128938 ON ticket (user_order_id)');
        $this->addSql('ALTER TABLE user_order ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_order ADD CONSTRAINT FK_17EB68C0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_17EB68C0A76ED395 ON user_order (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA36D128938');
        $this->addSql('DROP INDEX IDX_97A0ADA36D128938 ON ticket');
        $this->addSql('ALTER TABLE ticket CHANGE user_order_id user_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_order DROP FOREIGN KEY FK_17EB68C0A76ED395');
        $this->addSql('DROP INDEX IDX_17EB68C0A76ED395 ON user_order');
        $this->addSql('ALTER TABLE user_order DROP user_id');
    }
}
