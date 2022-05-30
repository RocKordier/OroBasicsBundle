<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity;

use EHDev\BasicsBundle\Entity\Base;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EHDev\BasicsBundle\Entity\Base
 */
class BaseTest extends TestCase
{
    private Base $testClass;

    public function setUp(): void
    {
        $this->testClass = new Base();
    }

    /**
     * @covers ::getId
     */
    public function testGetIdAreNull(): void
    {
        $this->assertNull($this->testClass->getId());
    }
}
