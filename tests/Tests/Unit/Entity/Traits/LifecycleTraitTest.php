<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity\Traits;

use EHDev\BasicsBundle\Tests\Fixture\LifecycleTraitTestClass;
use PHPUnit\Framework\TestCase;

class LifecycleTraitTest extends TestCase
{
    private LifecycleTraitTestClass $testClass;

    protected function setUp(): void
    {
        $this->testClass = new LifecycleTraitTestClass();
    }

    public function testPrePersist(): void
    {
        $this->testClass->prePersist();

        $this->assertEquals(new \DateTimeZone('UTC'), $this->testClass->getCreatedAt()->getTimezone());
        $this->assertEquals($this->testClass->getCreatedAt(), $this->testClass->getUpdatedAt());
    }

    public function testPreUpdate(): void
    {
        $before = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->testClass->preUpdate();

        $this->assertGreaterThanOrEqual($before, $this->testClass->getUpdatedAt());
    }
}
