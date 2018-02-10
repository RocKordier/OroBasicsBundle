<?php
namespace EHDev\BasicsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

trait OrganizationOwnerMigrationTrait
{
    private static function migrateOrganizationOwner(Table $table, Schema $schema)
    {
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);        $table->addIndex(['organization_id']);
        $table->addIndex(['organization_id']);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
    }

}
