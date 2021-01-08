<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210108170343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added audience field to performance';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE performances 
            ADD audience ENUM(\'adults\', \'kids\') DEFAULT \'adults\' NOT NULL COMMENT \'(DC2Type:AudienceEnum)\'
        ');

        $this->addSql('
            UPDATE performances 
            SET `audience` = \'kids\'
            WHERE `slug` IN (
                \'popieliushka\',
                \'zolotie-kurcha\',
                \'dorogha-do-sontsia\',
                \'aladdin\',
                \'korol-drozdoborod\',
                \'kit-u-chobotiakh-1\',
                \'troie-porosiat-1\',
                \'vsi-mishi-liubliat-sir\',
                \'vizok-chudies\',
                \'siroyizhka\',
                \'koza-dierieza\',
                \'korolieva-zagublienikh-g-udzikiv\',
                \'kotik-pivnik-i-lisichka\',
                \'snigova-korolieva\',
                \'rizdvianie-viertiepnie-diistvo\',
                \'prigodi-v-krayini-svitloforiyi\',
                \'barvinok-ghieroi\'
            )
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE performances DROP audience');
    }
}
