<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Enum\EmployeeStaffEnum;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425203035 extends AbstractMigration
{
    private const ENUM_STUFF = [
        EmployeeStaffEnum::ART_CORE,
        EmployeeStaffEnum::ART_PRODUCTION,
        EmployeeStaffEnum::ART_DIRECTOR,
        EmployeeStaffEnum::ADMINISTRATIVE,
        EmployeeStaffEnum::CREATIVE_CORE,
        EmployeeStaffEnum::INVITED_ACTOR,
        EmployeeStaffEnum::EPOCH,
    ];

    public function getDescription() : string
    {
        return 'Added Epoch enum to staff column at employees';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE employees MODIFY COLUMN staff ENUM("'.implode('","', self::ENUM_STUFF).'");');
    }

    public function down(Schema $schema) : void
    {
    }
}
