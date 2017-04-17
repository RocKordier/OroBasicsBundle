<?php

namespace EHDev\Bundle\BasicsBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use EHDev\Bundle\BasicsBundle\Migrations\Schema\v1_0\InitialTables;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class EHDevBasicsBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** v1_0 */
        InitialTables::createEhdevBasicsSCATable($schema);
        InitialTables::createEhdevBasicsContactTable($schema);

        InitialTables::addEhdevBasicSCAForeignKeys($schema);
        InitialTables::addEhdevBasicsContactForeignKeys($schema);
    }
}
