<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170402155012 extends AbstractMigration
{
    protected $venue_sector_slugs = [
        'Партер' => 'parterre',
        'Балкон' => 'balcony',
        'Лоджия Левая' => 'loggia-left',
    	'Лоджия Правая' => 'loggia-right',
    ];

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `venue_sector` ADD `slug` VARCHAR(255) NOT NULL');
    }

    public function postUp(Schema $schema)
    {
        $this->setVenueSectorSlugs();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `venue_sector` DROP slug');
    }

    private function setVenueSectorSlugs():void
    {
        $query = "SELECT * FROM `venue_sector`";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            if (empty($row['title']) || empty($this->venue_sector_slugs[$row['title']])) {
                continue;
            }
            $this->connection->update(
                'venue_sector',
                ['slug' => $this->venue_sector_slugs[$row['title']]],
                ['id' => $row['id']]
            );
        }
    }
}
