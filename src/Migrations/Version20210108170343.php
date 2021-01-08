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
        $this->addSql('ALTER TABLE performances ADD audience ENUM(\'A\', \'K\') DEFAULT NULL COMMENT \'(DC2Type:AudienceEnum)\'');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE performances DROP audience');
    }
}
