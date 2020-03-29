<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329094937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("UPDATE posts SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE posts SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE posts MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE posts MODIFY updatedBy varchar(255) NOT NULL");

        $this->addSql("UPDATE history SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE history SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE history MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE history MODIFY updatedBy varchar(255) NOT NULL");

        $this->addSql("UPDATE performances SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE performances SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE performances MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE performances MODIFY updatedBy varchar(255) NOT NULL");

        $this->addSql("UPDATE performance_schedule SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE performance_schedule SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE performance_schedule MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE performance_schedule MODIFY updatedBy varchar(255) NOT NULL");

        $this->addSql("UPDATE employees SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE employees SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE employees MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE employees MODIFY updatedBy varchar(255) NOT NULL");

        $this->addSql("UPDATE roles SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE roles SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE roles MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE roles MODIFY updatedBy varchar(255) NOT NULL");

        $this->addSql("UPDATE tags SET createdBy='roman' WHERE createdBy is NULL");
        $this->addSql("UPDATE tags SET updatedBy='roman' WHERE updatedBy is NULL");
        $this->addSql("ALTER TABLE tags MODIFY createdBy varchar(255) NOT NULL");
        $this->addSql("ALTER TABLE tags MODIFY updatedBy varchar(255) NOT NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
