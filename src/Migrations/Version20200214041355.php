<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200214041355 extends AbstractMigration
{
    protected $seasons = [
        ['number' => 83, 'startDate' => '2014-09-01 00:00:00', 'endDate' => '2015-07-31 23:59:59'],
        ['number' => 84, 'startDate' => '2015-09-01 00:00:00', 'endDate' => '2016-07-31 23:59:59'],
        ['number' => 85, 'startDate' => '2016-09-01 00:00:00', 'endDate' => '2017-07-31 23:59:59'],
        ['number' => 86, 'startDate' => '2017-09-01 00:00:00', 'endDate' => '2018-07-31 23:59:59'],
        ['number' => 87, 'startDate' => '2018-09-01 00:00:00', 'endDate' => '2019-07-31 23:59:59'],
        ['number' => 88, 'startDate' => '2019-09-01 00:00:00', 'endDate' => '2020-07-31 23:59:59'],
        ['number' => 89, 'startDate' => '2020-09-01 00:00:00', 'endDate' => '2021-07-31 23:59:59'],
        ['number' => 90, 'startDate' => '2021-09-01 00:00:00', 'endDate' => '2022-07-31 23:59:59'],
        ['number' => 91, 'startDate' => '2022-09-01 00:00:00', 'endDate' => '2023-07-31 23:59:59'],
        ['number' => 92, 'startDate' => '2023-09-01 00:00:00', 'endDate' => '2024-07-31 23:59:59'],
        ['number' => 93, 'startDate' => '2024-09-01 00:00:00', 'endDate' => '2025-07-31 23:59:59'],
        ['number' => 94, 'startDate' => '2025-09-01 00:00:00', 'endDate' => '2026-07-31 23:59:59'],
        ['number' => 95, 'startDate' => '2026-09-01 00:00:00', 'endDate' => '2027-07-31 23:59:59'],
        ['number' => 96, 'startDate' => '2027-09-01 00:00:00', 'endDate' => '2028-07-31 23:59:59'],
        ['number' => 97, 'startDate' => '2028-09-01 00:00:00', 'endDate' => '2029-07-31 23:59:59'],
        ['number' => 98, 'startDate' => '2029-09-01 00:00:00', 'endDate' => '2030-07-31 23:59:59'],
        ['number' => 99, 'startDate' => '2030-09-01 00:00:00', 'endDate' => '2031-07-31 23:59:59'],
    ];

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RepertoireSeason (id INT AUTO_INCREMENT NOT NULL, startDate DATETIME NOT NULL, endDate DATETIME NOT NULL, number INT NOT NULL, UNIQUE INDEX UNIQ_C9FDBB996901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE performance_repertoireseason (performance_id INT NOT NULL, repertoireseason_id INT NOT NULL, INDEX IDX_58AE5ABFB91ADEEE (performance_id), INDEX IDX_58AE5ABFAC76615 (repertoireseason_id), PRIMARY KEY(performance_id, repertoireseason_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE performance_repertoireseason ADD CONSTRAINT FK_58AE5ABFB91ADEEE FOREIGN KEY (performance_id) REFERENCES performances (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE performance_repertoireseason ADD CONSTRAINT FK_58AE5ABFAC76615 FOREIGN KEY (repertoireseason_id) REFERENCES RepertoireSeason (id) ON DELETE CASCADE');

        $this->insertSeasons();
        $this->addSeasonsToPerformances();
    }

    protected function addSeasonsToPerformances()
    {
        $file = __DIR__.'/data/theatreSeasons.csv';
        $performanceSeasons = $csv = array_map('str_getcsv', file($file));
        $keys = array_shift($performanceSeasons);

        foreach ($performanceSeasons as $performance) {
            $pId = array_shift($performance);
            $title = array_shift($performance);

            $psId = 6;
            while (!empty($performance)) {
                if (1 == array_shift($performance)) {
                    $this->addSql(
                        'INSERT INTO performance_repertoireseason (performance_id, repertoireseason_id) VALUES (:pId, :psId)',
                        ['pId' => $pId, 'psId' => $psId]
                    );
                }
                $psId--;
            }
        }

    }

    protected function insertSeasons()
    {
        foreach ($this->seasons as $season) {
            $this->addSql('INSERT INTO RepertoireSeason (startDate, endDate, number) VALUES (:startDate, :endDate, :number)', $season);
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE performance_repertoireseason DROP FOREIGN KEY FK_58AE5ABFAC76615');
        $this->addSql('DROP TABLE RepertoireSeason');
        $this->addSql('DROP TABLE performance_repertoireseason');
    }
}
