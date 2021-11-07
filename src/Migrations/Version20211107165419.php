<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107165419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove staff enum value field and column';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE employees DROP staff;');
    }

    public function down(Schema $schema): void
    {
        // sorry, but you can't revert this operation
    }
}
