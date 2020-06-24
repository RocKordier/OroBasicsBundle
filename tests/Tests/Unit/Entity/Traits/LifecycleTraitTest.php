<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity\Traits;

use EHDev\BasicsBundle\Tests\Fixture\LifecycleTraitTestClass;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EHDev\BasicsBundle\Entity\Traits\LifecycleTrait
 */
class LifecycleTraitTest extends TestCase
{
    private $testClass;

    public function setUp()
    {
        $this->testClass = new LifecycleTraitTestClass();
    }

    /**
     * @covers ::prePersist
     */
    public function testPrePersist()
    {
        $this->testClass->prePersist();

        $this->assertInstanceOf(\DateTime::class, $this->testClass->getCreatedAt());
        $this->assertEquals($this->testClass->getCreatedAt(), $this->testClass->getUpdatedAt());
    }

    /**
     * @covers ::preUpdate
     */
    public function testPreUpdate()
    {
        $this->testClass->preUpdate();

        $this->assertInstanceOf(\DateTime::class, $this->testClass->getUpdatedAt());
    }
}
