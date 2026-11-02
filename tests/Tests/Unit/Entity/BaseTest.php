<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity;

use EHDev\BasicsBundle\Entity\Base;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Base::class)]
class BaseTest extends TestCase
{
    private Base $testClass;

    protected function setUp(): void
    {
        $this->testClass = new Base();
    }

    public function testGetIdAreNull(): void
    {
        $this->assertNull($this->testClass->getId());
    }
}
