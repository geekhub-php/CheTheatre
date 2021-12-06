<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205200416 extends AbstractMigration
{
    private $positions = [
        'actors' => [
            'position' => 1,
            'parent' => 10,
        ],
        'art-core' => [
            'position' => 5,
            'parent' => 11,
        ],
        'ballet' => [
            'position' => 2,
            'parent' => 10,
        ],
        'administrative-accounting' => [
            'position' => 10,
            'parent' => 12,
        ],
        'orchestra' => [
            'position' => 3,
            'parent' => 10,
        ],
        'art-production' => [
            'position' => 6,
            'parent' => 11,
        ],
        'director' => [
            'position' => 8,
            'parent' => 12,
        ],
        'deputies' => [
            'position' => 9,
            'parent' => 12,
        ],
        'epoch' => [
            'position' => 11,
            'parent' => null,
        ],
    ];

    public function getDescription(): string
    {
        return 'Position and parent employee group data migration';
    }

    public function up(Schema $schema): void
    {

        $this->addSql("insert into employees_group (id, title, slug, position, createdAt, updatedAt, deletedAt, createdBy, updatedBy, deletedBy, parent_id) values (10, 'Творчий склад', 'creative', 0, '2021-11-21 07:53:04', '2021-11-26 08:44:01', null, 'admin', 'admin', null, null);");
        $this->addSql("insert into employees_group (id, title, slug, position, createdAt, updatedAt, deletedAt, createdBy, updatedBy, deletedBy, parent_id) values (11, 'Художній склад', 'artistic', 4, '2021-11-21 08:08:31', '2021-11-21 08:08:46', null, 'admin', 'admin', null, null);");
        $this->addSql("insert into employees_group (id, title, slug, position, createdAt, updatedAt, deletedAt, createdBy, updatedBy, deletedBy, parent_id) values (12, 'Адміністрація', 'administration', 7, '2021-11-21 08:10:10', '2021-11-21 08:11:01', null, 'admin', 'admin', null, null);");

        foreach ($this->positions as $slug => $data) {
            $this->addSql(
                'UPDATE employees_group SET position=?, parent_id=? WHERE slug=?', [
                    $data['position'],
                    $data['parent'],
                    $slug
                ]);
        }

        $this->addSql("insert into employee_group_translation (object_id, locale, field, content) values (10, 'en', 'title', 'Creative Composition');");
        $this->addSql("insert into employee_group_translation (object_id, locale, field, content) values (11, 'en', 'title', 'Artistic composition');");
        $this->addSql("insert into employee_group_translation (object_id, locale, field, content) values (12, 'en', 'title', 'Administration');");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
