<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Enum\EmployeeStaffEnum;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425203035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added Epoch enum to staff column at employees';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE employees MODIFY COLUMN staff ENUM("'.implode('","', EmployeeStaffEnum::$choices).'");');
    }

    public function down(Schema $schema) : void
    {
    }
}
