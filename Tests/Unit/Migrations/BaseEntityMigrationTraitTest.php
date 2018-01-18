<?php
namespace EHDev\BasicsBundle\Tests\Unit\Migrations;

use PHPUnit\Framework\TestCase;
use EHDev\BasicsBundle\Tests\Fixture\BaseMigration;
use Doctrine\DBAL\Schema\Table;

class BaseEntityMigrationTraitTest extends TestCase
{
    /** @var BaseMigration */
    private $testClass;

    public function up()
    {
        $this->testClass = new BaseMigration();
    }

    public function testMigrationTrait()
    {
        $table = new Table();

        $reflection = new \ReflectionClass(get_class($this->testClass));
        $method = $reflection->getMethod('migrateBaseEntity');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->testClass, [$table]);
    }
}
