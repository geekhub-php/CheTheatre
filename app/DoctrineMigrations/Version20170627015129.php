<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170627015129 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rows_for_sale ADD CONSTRAINT FK_BB4AF581137F3880 FOREIGN KEY (venueSector_id) REFERENCES venue_sector (id)');
        $this->addSql('ALTER TABLE ticket ADD userOrder_id VARCHAR(255) DEFAULT NULL, DROP user_order_id');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA358ACF019 FOREIGN KEY (userOrder_id) REFERENCES user_order (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA358ACF019 ON ticket (userOrder_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9C912ED9D ON users (api_key)');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_62534e219be8fd98 TO UNIQ_1483A5E99BE8FD98');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rows_for_sale DROP FOREIGN KEY FK_BB4AF581137F3880');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA358ACF019');
        $this->addSql('DROP INDEX IDX_97A0ADA358ACF019 ON ticket');
        $this->addSql('ALTER TABLE ticket ADD user_order_id INT DEFAULT NULL, DROP userOrder_id');
        $this->addSql('DROP INDEX UNIQ_1483A5E9C912ED9D ON users');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e99be8fd98 TO UNIQ_62534E219BE8FD98');
    }
}
