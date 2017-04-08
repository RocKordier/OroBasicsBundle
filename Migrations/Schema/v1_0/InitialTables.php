<?php

namespace EHDev\Bundle\BasicsBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class InitialTables
 *
 * @package EHDev\Bundle\BasicsBundle\Migrations\Schema\v1_0
 */
class InitialTables implements Migration
{
    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::createEhdevBasicsSCATable($schema);
        self::createEhdevBasicsContractTable($schema);

        self::addEhdevBasicSCAForeignKeys($schema);
        self::addEhdevBasicsContactForeignKeys($schema);
    }

    /**
     * Create ehdev_glb_guestlist table
     *
     * @param Schema $schema
     */
    public static function createEhdevBasicsSCATable(Schema $schema)
    {
        $table = $schema->createTable('ehdev_basics_sca');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('country_code', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('street', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('street2', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('city', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('organization', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('region_text', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('created_at', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated_at', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('serialized_data', 'array', ['notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->addIndex(['country_code'], 'idx_a7dd6f7af026bb7c', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['owner_id'], 'uniq_a7dd6f7a7e3c61f9');
        $table->addIndex(['region_code'], 'idx_a7dd6f7aaeb327af', []);
    }

    public static function createEhdevBasicsContractTable(Schema $schema)
    {
        $table = $schema->createTable('ehdev_basics_contact');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('address_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('business_unit_owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('gender', 'string', ['notnull' => false, 'length' => 8]);
        $table->addColumn('birthday', 'date', ['notnull' => false, 'comment' => '(DC2Type:date)']);
        $table->addColumn('email', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('phone', 'string', ['length' => 255]);
        $table->addColumn('skype', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('twitter', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('facebook', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created_at', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated_at', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('serialized_data', 'array', ['notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->addIndex(['last_name', 'first_name'], 'contact_name_idx', []);
        $table->addIndex(['organization_id'], 'idx_3cd54e1932c8a3de', []);
        $table->addUniqueIndex(['address_id'], 'uniq_3cd54e19f5b7af75');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['business_unit_owner_id'], 'idx_3cd54e1959294170', []);
    }

    /**
     * Add ehdev_glb_guestlist foreign keys.
     *
     * @param Schema $schema
     */
    public static function addEhdevBasicsContactForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ehdev_basics_contact');
        $table->addForeignKeyConstraint(
            $schema->getTable('ehdev_basics_sca'),
            ['address_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_business_unit'),
            ['business_unit_owner_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
    }

    public static function addEhdevBasicSCAForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ehdev_basics_sca');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_country'),
            ['country_code'],
            ['iso2_code'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_region'),
            ['region_code'],
            ['combined_code'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('ehdev_basics_contact'),
            ['owner_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
    }
}
