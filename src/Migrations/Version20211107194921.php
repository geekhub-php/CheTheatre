<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107194921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE history CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE performance_schedule CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE performances CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE roles CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tags CHANGE createdBy createdBy VARCHAR(255) DEFAULT NULL, CHANGE updatedBy updatedBy VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE history CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE performance_schedule CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE performances CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE posts CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE roles CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE tags CHANGE createdBy createdBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE updatedBy updatedBy VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
