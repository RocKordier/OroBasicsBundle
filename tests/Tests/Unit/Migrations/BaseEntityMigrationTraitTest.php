<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Migrations;

use Doctrine\DBAL\Schema\Table;
use EHDev\BasicsBundle\Tests\Fixture\BaseEntityMigrationTraitTestClass;
use EHDev\BasicsBundle\Tests\Util\PHPUnitUtil;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EHDev\BasicsBundle\Migrations\BaseEntityMigrationTrait
 */
class BaseEntityMigrationTraitTest extends TestCase
{
    public function testMigrationTrait(): void
    {
        $table = new Table('TestTable');
        $migrateBaseEntityMethod = PHPUnitUtil::callMethod(new BaseEntityMigrationTraitTestClass(), 'migrateBaseEntity', [$table]);

        $this->assertNull($migrateBaseEntityMethod);
    }
}
