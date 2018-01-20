<?php
namespace EHDev\BasicsBundle\Tests\Unit\Migrations;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\IntegerType;
use EHDev\Utility\CIUtility\Tests\Utility\PHPUnitUtil;
use PHPUnit\Framework\TestCase;
use EHDev\BasicsBundle\Tests\Fixture\BaseEntityMigrationTraitTestClass;
use Doctrine\DBAL\Schema\Table;

/**
 * @coversDefaultClass EHDev\BasicsBundle\Migrations\BaseEntityMigrationTrait
 */
class BaseEntityMigrationTraitTest extends TestCase
{
    /**
     * @covers ::migrateBaseEntity
     */
    public function testMigrationTrait()
    {
        try {
            $table = new Table('TestTable');
        } catch (\Exception $e) {
            $this->markAsRisky();
        }
        $migrateBaseEntityMethod = PHPUnitUtil::callMethod(new BaseEntityMigrationTraitTestClass(), 'migrateBaseEntity', [$table]);

        $this->assertTrue($table->hasColumn('id'));
        $this->assertTrue($table->hasColumn('created_at'));
        $this->assertTrue($table->hasColumn('updated_at'));
        $this->assertInstanceOf(IntegerType::class, $table->getColumn('id')->getType());
        $this->assertInstanceOf(DateTimeType::class, $table->getColumn('created_at')->getType());
        $this->assertInstanceOf(DateTimeType::class, $table->getColumn('updated_at')->getType());
        $this->assertTrue($table->hasPrimaryKey());
        $this->assertNull($migrateBaseEntityMethod);
    }
}
