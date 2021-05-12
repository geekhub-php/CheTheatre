<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210504022820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Employees Group table migration';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE employee_group_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_97B6048F232D562B (object_id), UNIQUE INDEX lookup_unique_employee_group_translation_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employees_group (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, position INT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, createdBy VARCHAR(255) DEFAULT NULL, updatedBy VARCHAR(255) DEFAULT NULL, deletedBy VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee_group_translation ADD CONSTRAINT FK_97B6048F232D562B FOREIGN KEY (object_id) REFERENCES employees_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employees ADD employeeGroup_id INT');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300F3260C63 FOREIGN KEY (employeeGroup_id) REFERENCES employees_group (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_BA82C300F3260C63 ON employees (employeeGroup_id)');
    }

    public function down(Schema $schema) : void
    {
    }
}
