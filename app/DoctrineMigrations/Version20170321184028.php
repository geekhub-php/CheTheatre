<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321184028 extends AbstractMigration
{
    protected $venues = [
        'venue-philharmonic' => 1,
        'venue-kilic-house' => 2,
        'venue-theatre' => 3,
        'venue-salut' => 4,
        'venue-palac_molodi' => 5,
        'venue-center_of_kids_arts' => 6,
        'venue-cherkasy-art-museum' => 7,
    ];

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on "mysql".'
        );

        $this->addSql('CREATE TABLE venue (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, hallTemplate LONGTEXT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, deletedBy VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE venue_sector (id INT AUTO_INCREMENT NOT NULL, venue_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_53CC259240A73EBA (venue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seat_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_9132FE65232D562B (object_id), UNIQUE INDEX lookup_unique_seat_translation_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE venue_sector_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_B4A75B1B232D562B (object_id), UNIQUE INDEX lookup_unique_venue_sector_translation_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE venue_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_A2B005A232D562B (object_id), UNIQUE INDEX lookup_unique_venue_translation_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_category_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_81449D73232D562B (object_id), UNIQUE INDEX lookup_unique_price_category_translation_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seat (id INT AUTO_INCREMENT NOT NULL, row INT NOT NULL, place INT NOT NULL, venueSector_id INT DEFAULT NULL, priceCategory_id INT DEFAULT NULL, INDEX IDX_3D5C3666137F3880 (venueSector_id), INDEX IDX_3D5C36664319ED49 (priceCategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_category (id INT AUTO_INCREMENT NOT NULL, venue_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, INDEX IDX_64FA22D640A73EBA (venue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE venue_sector ADD CONSTRAINT FK_53CC259240A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id)');
        $this->addSql('ALTER TABLE seat_translation ADD CONSTRAINT FK_9132FE65232D562B FOREIGN KEY (object_id) REFERENCES seat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE venue_sector_translation ADD CONSTRAINT FK_B4A75B1B232D562B FOREIGN KEY (object_id) REFERENCES venue_sector (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE venue_translation ADD CONSTRAINT FK_A2B005A232D562B FOREIGN KEY (object_id) REFERENCES venue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_category_translation ADD CONSTRAINT FK_81449D73232D562B FOREIGN KEY (object_id) REFERENCES price_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C3666137F3880 FOREIGN KEY (venueSector_id) REFERENCES venue_sector (id)');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C36664319ED49 FOREIGN KEY (priceCategory_id) REFERENCES price_category (id)');
        $this->addSql('ALTER TABLE price_category ADD CONSTRAINT FK_64FA22D640A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id)');
        $this->addSql('ALTER TABLE performance_schedule ADD venue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE performance_schedule ADD CONSTRAINT FK_D12575F940A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id)');
        $this->addSql('CREATE INDEX IDX_D12575F940A73EBA ON performance_schedule (venue_id)');
    }

    public function postUp(Schema $schema)
    {
        $this->addVenues();
        $this->setVenueIdForPerformanceShedule();
        $this->connection->executeQuery('ALTER TABLE performance_schedule DROP venue');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on "mysql".'
        );

        $this->addSql('ALTER TABLE performance_schedule DROP FOREIGN KEY FK_D12575F940A73EBA');
        $this->addSql('ALTER TABLE venue_sector DROP FOREIGN KEY FK_53CC259240A73EBA');
        $this->addSql('ALTER TABLE venue_translation DROP FOREIGN KEY FK_A2B005A232D562B');
        $this->addSql('ALTER TABLE price_category DROP FOREIGN KEY FK_64FA22D640A73EBA');
        $this->addSql('ALTER TABLE venue_sector_translation DROP FOREIGN KEY FK_B4A75B1B232D562B');
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C3666137F3880');
        $this->addSql('ALTER TABLE seat_translation DROP FOREIGN KEY FK_9132FE65232D562B');
        $this->addSql('ALTER TABLE price_category_translation DROP FOREIGN KEY FK_81449D73232D562B');
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C36664319ED49');
        $this->addSql('DROP TABLE venue');
        $this->addSql('DROP TABLE venue_sector');
        $this->addSql('DROP TABLE seat_translation');
        $this->addSql('DROP TABLE venue_sector_translation');
        $this->addSql('DROP TABLE venue_translation');
        $this->addSql('DROP TABLE price_category_translation');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE price_category');
        $this->addSql('DROP INDEX IDX_D12575F940A73EBA ON performance_schedule');
        $this->addSql('ALTER TABLE performance_schedule ADD venue VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP venue_id');
    }

    private function addVenues()
    {
        $now = new \DateTime();

        // --------- Cherkasy Philharmonic --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Черкаська Філармонія',
                'address' => 'вулиця Хрещатик, 196, Черкаси, Черкаська область, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-philharmonic'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Cherkasy Philharmonic',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Khreshchatyk Street, 196, Cherkasy region, Ukraine, 18000',
            ]
        );

        // --------- Kulik's House of Culture --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Будинок культури ім. Кулика',
                'address' => 'вулиця Благовісна, 170, Черкаси, Черкаська область, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-kilic-house'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Kulik\'s House of Culture',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Blahovisna Street 170, Cherkasy region, Ukraine, 18000',
            ]
        );

        // --------- Cherkasy Theatre --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Черкаський Театр',
                'address' => 'бульвар Шевченка, 234, Черкаси, Черкаська, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-theatre'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Cherkasy Theatre',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Boulevard Shevchenko, 234 Cherkasy, Ukraine, 18000',
            ]
        );

        // --------- Cinema "Salute" --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Кінотеатр "Салют"',
                'address' => 'вулиця Хрещатик, 170, Черкаси, Черкаська область, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-salut'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Cinema "Salute"',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Khreshchatyk Street, 170, Cherkasy region, Ukraine, 18000',
            ]
        );

        // --------- Cherkasky City Palace of Youth --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Черкаський міський Палац молоді',
                'address' => 'вулиця Сумгаїтська, 12, Черкаси, Черкаська область, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-palac_molodi'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Cherkasky City Palace of Youth',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Street Sumgait, 12, Cherkasy region, Ukraine, 18000',
            ]
        );

        // --------- Center for Children and Youth --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Центр дитячої та юнацької творчості',
                'address' => 'вулиця Смілянська, 33, Черкаси, Черкаська область, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-center_of_kids_arts'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Center for Children and Youth',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Smilians\'ka Street, 33, Cherkasy region, Ukraine, 18000',
            ]
        );

        // --------- Cherkassy Regional Art Museum --------------//
        $this->connection->insert(
            'venue',
            [
                'title' => 'Черкаський обласний художній музей',
                'address' => 'вулиця Хрещатик, 259, Черкаси, Черкаська область, Україна, 18000',
                'hallTemplate' => '<div></div>',
                'createdAt' => $now->format('Y-m-d H:i:s'),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->venues['venue-cherkasy-art-museum'] = $id;
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'title',
                'content' => 'Cherkassy Regional Art Museum',
            ]
        );
        $this->connection->insert(
            'venue_translation',
            [
                'object_id' => $id,
                'locale' => 'en',
                'field' => 'address',
                'content' => 'Khreshchatyk Street, 259, Cherkasy region, Ukraine, 18000',
            ]
        );
    }

    private function setVenueIdForPerformanceShedule():void
    {
        $query = "SELECT * FROM performance_schedule";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            if (empty($row['venue'])) {
                continue;
            }
            $this->connection->update(
                'performance_schedule',
                ['venue_id' => $this->venues[$row['venue']]],
                ['id' => $row['id']]
            );
        }
    }
}
