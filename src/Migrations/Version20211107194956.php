<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\EntityManagerAwareInterface;
use App\Doctrine\EntityManagerAwareTrait;
use App\Entity\Employee;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107194956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Reset order positions for employees';
    }

    public function up(Schema $schema): void
    {
        $employees = $this->connection->fetchAllAssociative('SELECT * FROM employees WHERE deletedAt IS NOT NULL ORDER BY orderPosition');
        $i = pow(10, 6);
        foreach ($employees as $employee) {
            $this->addSql('UPDATE employees SET orderPosition=? WHERE id=?', [$i, $employee['id']]);
            $i++;
        }

        $groups = $this->connection->fetchAllAssociative('SELECT distinct employeeGroup_id FROM employees WHERE employeeGroup_id IS NOT NULL');
        foreach ($groups as $group) {
            $employees = $this->connection->fetchAllAssociative(
                'SELECT * FROM employees WHERE deletedAt IS NULL AND employeeGroup_id=? ORDER BY orderPosition',
                [$group['employeeGroup_id']]
            );
            $i = 1;
            foreach ($employees as $employee) {
                $this->addSql('UPDATE employees SET orderPosition=? WHERE id=?', [$i, $employee['id']]);
                $i++;
            }
        }

    }

    public function down(Schema $schema): void
    {
        // can't go back
    }
}
