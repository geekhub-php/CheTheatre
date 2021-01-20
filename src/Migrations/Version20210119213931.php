<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210119213931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added new enum value: art-director';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE employees  
            CHANGE staff staff ENUM(\'administrative\', \'art-director\', \'art-production\', \'art-core\', \'creative\', \'invited\') 
            DEFAULT \'creative\' NOT NULL COMMENT \'(DC2Type:EmployeeStaffEnum)\'
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE employees  
            CHANGE staff staff ENUM(\'administrative\', \'art-production\', \'art-core\', \'creative\', \'invited\') 
            DEFAULT \'creative\' NOT NULL COMMENT \'(DC2Type:EmployeeStaffEnum)\'
        ');
    }
}
