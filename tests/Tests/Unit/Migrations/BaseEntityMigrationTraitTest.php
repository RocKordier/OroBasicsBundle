<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Migrations;

use Doctrine\DBAL\Schema\Table;
use EHDev\BasicsBundle\Tests\Fixture\BaseEntityMigrationTraitTestClass;
use EHDev\Utility\CIUtility\Tests\Utility\PHPUnitUtil;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EHDev\BasicsBundle\Migrations\BaseEntityMigrationTrait
 */
class BaseEntityMigrationTraitTest extends TestCase
{
    public function testMigrationTrait()
    {
        try {
            $table = new Table('TestTable');
        } catch (\Exception $e) {
            $this->markAsRisky();
        }
        $migrateBaseEntityMethod = PHPUnitUtil::callMethod(new BaseEntityMigrationTraitTestClass(), 'migrateBaseEntity', [$table]);

        $this->assertNull($migrateBaseEntityMethod);
    }
}
