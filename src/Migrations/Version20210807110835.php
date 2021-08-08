<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210807110835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added orderPosition property to employee entity';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE employees ADD `orderPosition` INT DEFAULT 0 NOT NULL
        ');
        $this->addSql('SET @inc = -1');
        $this->addSql('
            UPDATE employees 
            SET `orderPosition` = @inc := @inc + 1 
            WHERE `deletedAt` IS NULL 
            ORDER BY `lastName`, `firstName`
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE employees DROP `orderPosition`');
    }
}
