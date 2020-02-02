<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200131091633 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Fixed DeleteAt for Roles which is part of deleted performances';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
<<<SQL
UPDATE roles
INNER JOIN performances ON performances.id = roles.performance_id
SET 
    roles.deletedAt = performances.deletedAt,
    roles.deletedBy = 'migration20200131091633'
WHERE
    performances.deletedAt IS NOT NULL
    AND roles.deletedAt IS NUll
;
SQL
        );
    }

    public function down(Schema $schema) : void
    {}
}
