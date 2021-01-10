<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210110212716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added flag for 2FA enabled by email';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE users ADD emailAuthEnabled TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE users DROP emailAuthEnabled');
    }
}
