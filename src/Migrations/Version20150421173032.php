<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\StringType;
use Doctrine\Migrations\AbstractMigration;

class Version20150421173032 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE  `performances` CHANGE  `description`  `description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL');
        $this->addSql('ALTER TABLE  `employees` CHANGE  `biography`  `biography` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL');
        $this->addSql('ALTER TABLE  `roles` CHANGE  `description`  `description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
    }
}
