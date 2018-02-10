<?php
namespace EHDev\BasicsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

trait UserOwnerMigrationTrait
{
    use OrganizationOwnerMigrationTrait;

    private static function migrationBUOwner(Table $table, Schema $schema)
    {
        $table->addColumn('user_owner_id', 'integer', ['notnull' => false]);
        $table->addIndex(['user_owner_id']);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_owner_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );

        self::migrateOrganizationOwner($table, $schema);
    }
}
