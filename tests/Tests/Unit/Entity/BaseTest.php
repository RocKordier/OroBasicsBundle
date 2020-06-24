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
    private $testClass;

    public function setUp()
    {
        $this->testClass = new Base();
    }

    /**
     * @covers ::getId
     */
    public function testGetIdAreNull()
    {
        $this->assertNull($this->testClass->getId());
    }
}
