<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170326151604 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_order (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', status enum(\'free\', \'booked\', \'ordered\', \'opened\', \'closed\'), createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', seat_id INT NOT NULL, performance_event_id INT NOT NULL, series_date DATETIME NOT NULL, series_number VARCHAR(10) NOT NULL, price INT NOT NULL, status enum(\'free\', \'booked\', \'paid\', \'offline\'), createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, user_order_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\', INDEX IDX_97A0ADA3C1DAFE35 (seat_id), INDEX IDX_97A0ADA3FAEA8C89 (performance_event_id), INDEX IDX_97A0ADA31C7C4AAD (user_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3FAEA8C89 FOREIGN KEY (performance_event_id) REFERENCES performance_schedule (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA31C7C4AAD FOREIGN KEY (user_order_id) REFERENCES user_order (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA31C7C4AAD');
        $this->addSql('DROP TABLE user_order');
        $this->addSql('DROP TABLE ticket');
    }
}
