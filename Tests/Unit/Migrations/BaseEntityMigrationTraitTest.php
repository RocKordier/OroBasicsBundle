<?php
namespace EHDev\BasicsBundle\Tests\Unit\Migrations;

use EHDev\BasicsBundle\Tests\PHPUnitUtil;
use PHPUnit\Framework\TestCase;
use EHDev\BasicsBundle\Tests\Fixture\BaseEntityMigrationTraitTestClass;
use Doctrine\DBAL\Schema\Table;

/**
 * @coversDefaultClass EHDev\BasicsBundle\Migrations\BaseEntityMigrationTrait
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
