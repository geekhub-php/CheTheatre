<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425203036 extends AbstractMigration
{
    private const DATA = __DIR__.'/data/performances_duration_producers_age_limits.csv';

    public function getDescription() : string
    {
        return 'Added: performance age limit data, performance producer field with data, performance len with data';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE performances ADD producer_id INT DEFAULT NULL, ADD durationInMin INT NOT NULL, ADD extProducer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE performances ADD CONSTRAINT FK_8133AB2B89B658FE FOREIGN KEY (producer_id) REFERENCES employees (id)');
        $this->addSql('CREATE INDEX IDX_8133AB2B89B658FE ON performances (producer_id)');
        $this->insertData();
    }

    public function down(Schema $schema) : void
    {
    }

    public function insertData()
    {
        $performances = array_map('str_getcsv', file(self::DATA));
        array_shift($performances); // remove headers

        foreach ($performances as $performance) {
            $slug = array_shift($performance);
            $title = array_shift($performance);
            $link = array_shift($performance);
            $ageLimit = array_shift($performance);
            $producer = array_shift($performance);
            $len = array_shift($performance);

            $inHouseProducer = $this->connection->fetchAssociative(
                'SELECT id FROM employees WHERE lastName LIKE :lastName',
                ['lastName' => '%'.trim(explode(' ', $producer)[0]).'%']
            );

            if ($inHouseProducer) {
                $this->addSql(
                    'UPDATE performances SET durationInMin=:len, ageLimit=:ageLimit, producer_id=:producer WHERE slug=:slug',
                    ['len' => (int) $len, 'ageLimit' => (int) $ageLimit, 'producer' => $inHouseProducer['id'], 'slug' => $slug]
                );
                continue;
            }

            $this->addSql(
                'UPDATE performances SET durationInMin=:len, ageLimit=:ageLimit, extProducer=:producer WHERE slug=:slug',
                ['len' => (int) $len, 'ageLimit' => (int) $ageLimit, 'producer' => $producer, 'slug' => $slug]
            );
        }
    }
}
