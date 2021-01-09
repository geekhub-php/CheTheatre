<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210109113511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added stuff categories to employee entity';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE employees 
            ADD staff 
                ENUM(\'art-core\', \'art-production\', \'administrative\', \'creative\', \'invited\') 
                DEFAULT \'creative\' NOT NULL COMMENT \'(DC2Type:EmployeeStaffEnum)\'
        ');

        $this->addSql('
            UPDATE employees 
            SET `staff` = \'art-core\'
            WHERE `position` IN (
                \'acting_artistic_director\',
                \'conductor\',
                \'costumer\',
                \'head_of_the_literary_and_dramatic_part\'
            )
        ');

        $this->addSql('
            UPDATE employees 
            SET `staff` = \'art-production\'
            WHERE `position` IN (
                \'art_director\',
                \'costumer\',
                \'leading_artist_scene\',
                \'main_artist\',
                \'main_choreographer\',
                \'stage_manager\',
                \'head_of_the_literary_and_dramatic_part\'
            )
        ');

        $this->addSql('
            UPDATE employees 
            SET `staff` = \'administrative\'
            WHERE `position` IN (
                \'theatre_director\',
                \'theatre_director_art_director\'
            )
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE employees DROP staff');
    }
}
