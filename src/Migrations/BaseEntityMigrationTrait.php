<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Migrations;

use Doctrine\DBAL\Schema\Table;

trait BaseEntityMigrationTrait
{
    private static function migrateBaseEntity(Table $table): void
    {
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('created_at', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated_at', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->setPrimaryKey(['id']);
    }
}
