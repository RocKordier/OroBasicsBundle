<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use EHDev\BasicsBundle\Tests\Fixture\BUOwnerMigrationTraitTestClass;
use EHDev\BasicsBundle\Tests\Fixture\OrganizationOwnerMigrationTraitTestClass;
use EHDev\BasicsBundle\Tests\Fixture\UserOwnerMigrationTraitTestClass;
use EHDev\BasicsBundle\Tests\Util\PHPUnitUtil;
use PHPUnit\Framework\TestCase;

final class OwnerMigrationTraitTest extends TestCase
{
    private Schema $schema;
    private Table $table;

    protected function setUp(): void
    {
        $this->schema = new Schema();
        foreach (['oro_organization', 'oro_business_unit', 'oro_user'] as $tableName) {
            $this->schema->createTable($tableName)->addColumn('id', 'integer');
        }
        $this->table = $this->schema->createTable('acme_entity');
    }

    public function testOrganizationOwner(): void
    {
        PHPUnitUtil::callMethod(new OrganizationOwnerMigrationTraitTestClass(), 'migrateOrganizationOwner', [$this->table, $this->schema]);

        self::assertSame(['oro_organization' => ['organization_id']], $this->getForeignKeys());
    }

    public function testBusinessUnitOwner(): void
    {
        PHPUnitUtil::callMethod(new BUOwnerMigrationTraitTestClass(), 'migrationBUOwner', [$this->table, $this->schema]);

        self::assertSame([
            'oro_business_unit' => ['business_unit_owner_id'],
            'oro_organization' => ['organization_id'],
        ], $this->getForeignKeys());
    }

    public function testUserOwner(): void
    {
        PHPUnitUtil::callMethod(new UserOwnerMigrationTraitTestClass(), 'migrationBUOwner', [$this->table, $this->schema]);

        self::assertSame([
            'oro_organization' => ['organization_id'],
            'oro_user' => ['user_owner_id'],
        ], $this->getForeignKeys());
    }

    /**
     * @return array<string, list<string>>
     */
    private function getForeignKeys(): array
    {
        $foreignKeys = [];
        foreach ($this->table->getForeignKeys() as $foreignKey) {
            $foreignKeys[$foreignKey->getForeignTableName()] = array_values($foreignKey->getLocalColumns());
        }
        ksort($foreignKeys);

        return $foreignKeys;
    }
}
