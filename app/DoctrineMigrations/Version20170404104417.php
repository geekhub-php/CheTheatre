<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404104417 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE performance_schedule ADD seriesNumber VARCHAR(10) DEFAULT NULL, ADD seriesDate DATETIME DEFAULT NULL, ADD enableSale TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE price_category DROP FOREIGN KEY FK_64FA22D640A73EBA');
        $this->addSql('DROP INDEX IDX_64FA22D640A73EBA ON price_category');
        $this->addSql('ALTER TABLE price_category ADD places VARCHAR(255) DEFAULT NULL, ADD price INT NOT NULL, ADD venueSector_id INT DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT \'gray\' NOT NULL, CHANGE title rows VARCHAR(255) NOT NULL, CHANGE venue_id performanceEvent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE price_category ADD CONSTRAINT FK_64FA22D65F1583FA FOREIGN KEY (performanceEvent_id) REFERENCES performance_schedule (id)');
        $this->addSql('ALTER TABLE price_category ADD CONSTRAINT FK_64FA22D6137F3880 FOREIGN KEY (venueSector_id) REFERENCES venue_sector (id)');
        $this->addSql('CREATE INDEX IDX_64FA22D65F1583FA ON price_category (performanceEvent_id)');
        $this->addSql('CREATE INDEX IDX_64FA22D6137F3880 ON price_category (venueSector_id)');
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C36664319ED49');
        $this->addSql('DROP INDEX IDX_3D5C36664319ED49 ON seat');
        $this->addSql('ALTER TABLE seat DROP priceCategory_id');
        $this->addSql('ALTER TABLE user_order CHANGE status status enum(\'free\', \'booked\', \'ordered\', \'opened\', \'closed\')');
        $this->addSql('ALTER TABLE ticket CHANGE status status enum(\'free\', \'booked\', \'paid\', \'offline\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_order CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE performance_schedule DROP seriesNumber, DROP seriesDate, DROP enableSale');
        $this->addSql('ALTER TABLE price_category DROP FOREIGN KEY FK_64FA22D65F1583FA');
        $this->addSql('ALTER TABLE price_category DROP FOREIGN KEY FK_64FA22D6137F3880');
        $this->addSql('DROP INDEX IDX_64FA22D65F1583FA ON price_category');
        $this->addSql('DROP INDEX IDX_64FA22D6137F3880 ON price_category');
        $this->addSql('ALTER TABLE price_category ADD venue_id INT DEFAULT NULL, DROP places, DROP price, DROP performanceEvent_id, DROP venueSector_id, CHANGE color color VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE rows title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE price_category ADD CONSTRAINT FK_64FA22D640A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id)');
        $this->addSql('CREATE INDEX IDX_64FA22D640A73EBA ON price_category (venue_id)');
        $this->addSql('ALTER TABLE seat ADD priceCategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C36664319ED49 FOREIGN KEY (priceCategory_id) REFERENCES price_category (id)');
        $this->addSql('CREATE INDEX IDX_3D5C36664319ED49 ON seat (priceCategory_id)');
        $this->addSql('ALTER TABLE ticket CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
