<?php

namespace Oro\Tests\Connection;

use Doctrine\ORM\Tools\SchemaTool;
use Oro\Tests\TestCase;

class TearDownTest extends TestCase
{
    public function testDropSchema()
    {
        $connection    = $this->entityManager->getConnection();
        $schemaManager = $connection->getSchemaManager();
        $metadatas     = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $tool = new SchemaTool($this->entityManager);
        $tool->dropSchema($metadatas);

        $this->assertEmpty($schemaManager->listTables());
    }

    public function testDropDatabase()
    {
        $connection    = $this->entityManager->getConnection();
        $schemaManager = $connection->getSchemaManager();
        $params        = $connection->getParams();

        if (isset($params['master'])) {
            $params = $params['master'];
        }

        $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);

        if (!isset($params['path'])) {
            $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $schemaManager->dropDatabase($name);

        if ($params['path']) {
            $this->assertFileNotExists($name);
        } else {
            $this->assertNotContains($name, $schemaManager->listDatabases());
        }
    }
}
