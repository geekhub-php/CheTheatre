<?php

namespace App\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class MigrationFactory implements \Doctrine\Migrations\Version\MigrationFactory
{
    /** @var Connection */
    private $connection;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Connection $connection, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->logger     = $logger;
        $this->em = $em;
    }

    public function createVersion(string $migrationClassName) : AbstractMigration
    {
        $migration = new $migrationClassName(
            $this->connection,
            $this->logger
        );

        // or you can ommit this check
        if ($migration instanceof EntityManagerAwareInterface) {
            $migration->setEntityManager($this->em);
        }

        return $migration;
    }
}
