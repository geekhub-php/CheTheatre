<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210110143824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added 2FA properties to user';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE users ADD email VARCHAR(255) NOT NULL, ADD authCode INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_2da17977f85e0677 TO UNIQ_1483A5E9F85E0677');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON users');
        $this->addSql('ALTER TABLE users DROP email, DROP authCode');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e9f85e0677 TO UNIQ_2DA17977F85E0677');
    }
}
