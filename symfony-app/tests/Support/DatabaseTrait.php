<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait DatabaseTrait
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->resetDatabase();
    }

    protected function tearDown(): void
    {
        $this->resetDatabase();
        parent::tearDown();
    }

    private function resetDatabase(): void
    {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $connection = $em->getConnection();

        // Get all tables
        $platform = $connection->getDatabasePlatform();
        $tables = $connection->createSchemaManager()->listTableNames();

        // Disable foreign key constraints
        $connection->executeStatement('SET CONSTRAINTS ALL DEFERRED');

        // Truncate all tables
        foreach ($tables as $table) {
            try {
                $connection->executeStatement(
                    $platform->getTruncateTableSQL($table, true)
                );
            } catch (\Exception $e) {
                // Ignore errors if table doesn't exist
            }
        }

        // Re-enable foreign key constraints
        $connection->executeStatement('SET CONSTRAINTS ALL IMMEDIATE');
    }
}
