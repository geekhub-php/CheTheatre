<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200316192653 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Made short description as long text';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE posts MODIFY shortDescription LONGTEXT;');

    }

    public function down(Schema $schema) : void
    {
    }
}
