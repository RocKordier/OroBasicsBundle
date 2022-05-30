<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity\Traits;

use EHDev\BasicsBundle\Tests\Fixture\CreatedUpdatedTraitTestClass;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EHDev\BasicsBundle\Entity\Traits\CreatedUpdatedTrait
 */
class CreatedUpdatedTraitTest extends TestCase
{
    private CreatedUpdatedTraitTestClass $testClass;

    public function setUp(): void
    {
        $this->testClass = new CreatedUpdatedTraitTestClass();
    }

    /**
     * @covers ::setCreatedAt
     */
    public function testSetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->assertEquals($this->testClass, $this->testClass->setCreatedAt($createdAt));
    }

    /**
     * @covers ::getCreatedAt
     */
    public function testGetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->testClass->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->testClass->getCreatedAt());
    }

    /**
     * @covers ::setUpdatedAt
     */
    public function testSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->assertEquals($this->testClass, $this->testClass->setUpdatedAt($updatedAt));
    }

    /**
     * @covers ::getUpdatedAt
     */
    public function testGetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->testClass->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->testClass->getUpdatedAt());
    }
}
