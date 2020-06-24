<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

trait BUOwnerMigrationTrait
{
    use OrganizationOwnerMigrationTrait;

    private static function migrationBUOwner(Table $table, Schema $schema)
    {
        $table->addColumn('business_unit_owner_id', 'integer', ['notnull' => false]);
        $table->addIndex(['business_unit_owner_id']);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_business_unit'),
            ['business_unit_owner_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );

        self::migrateOrganizationOwner($table, $schema);
    }
}
