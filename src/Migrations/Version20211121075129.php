<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211121075129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added parent to the employee group';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees_group ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employees_group ADD CONSTRAINT FK_C6D43F24727ACA70 FOREIGN KEY (parent_id) REFERENCES employees_group (id)');
        $this->addSql('CREATE INDEX IDX_C6D43F24727ACA70 ON employees_group (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees_group DROP FOREIGN KEY FK_C6D43F24727ACA70');
        $this->addSql('DROP INDEX IDX_C6D43F24727ACA70 ON employees_group');
        $this->addSql('ALTER TABLE employees_group DROP parent_id');
    }
}
