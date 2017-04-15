<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170415072527 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE performanceevent_rowsforsale (performanceevent_id INT NOT NULL, rowsforsale_id INT NOT NULL, INDEX IDX_4B0B0CD3A663E1AC (performanceevent_id), INDEX IDX_4B0B0CD3A26F6674 (rowsforsale_id), PRIMARY KEY(performanceevent_id, rowsforsale_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rows_for_sale (id INT AUTO_INCREMENT NOT NULL, row INT NOT NULL, venueSector_id INT DEFAULT NULL, INDEX IDX_BB4AF581137F3880 (venueSector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rows_for_sale_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_AA8BD619232D562B (object_id), UNIQUE INDEX lookup_unique_rows_for_sale_translation_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE performanceevent_rowsforsale ADD CONSTRAINT FK_4B0B0CD3A663E1AC FOREIGN KEY (performanceevent_id) REFERENCES performance_schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE performanceevent_rowsforsale ADD CONSTRAINT FK_4B0B0CD3A26F6674 FOREIGN KEY (rowsforsale_id) REFERENCES rows_for_sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rows_for_sale ADD CONSTRAINT FK_BB4AF581137F3880 FOREIGN KEY (venueSector_id) REFERENCES venue_sector (id)');
        $this->addSql('ALTER TABLE rows_for_sale_translation ADD CONSTRAINT FK_AA8BD619232D562B FOREIGN KEY (object_id) REFERENCES rows_for_sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD role VARCHAR(50) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE performanceevent_rowsforsale DROP FOREIGN KEY FK_4B0B0CD3A26F6674');
        $this->addSql('ALTER TABLE rows_for_sale_translation DROP FOREIGN KEY FK_AA8BD619232D562B');
        $this->addSql('DROP TABLE performanceevent_rowsforsale');
        $this->addSql('DROP TABLE rows_for_sale');
        $this->addSql('DROP TABLE rows_for_sale_translation');
        $this->addSql('ALTER TABLE users DROP role');
    }
}
