<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210410060602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Age Limit to the Performance';
    }

    public function up(Schema $schema) : void
    {
        $schema
            ->getTable("performances")
            ->addColumn("ageLimit", "integer", ['notnull' => true, 'default' => 0])
        ;
    }

    public function down(Schema $schema) : void
    {
    }
}
