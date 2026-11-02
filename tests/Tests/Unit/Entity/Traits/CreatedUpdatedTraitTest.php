<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity\Traits;

use EHDev\BasicsBundle\Tests\Fixture\CreatedUpdatedTraitTestClass;
use PHPUnit\Framework\TestCase;

class CreatedUpdatedTraitTest extends TestCase
{
    private CreatedUpdatedTraitTestClass $testClass;

    protected function setUp(): void
    {
        $this->testClass = new CreatedUpdatedTraitTestClass();
    }

    public function testSetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->assertEquals($this->testClass, $this->testClass->setCreatedAt($createdAt));
    }

    public function testGetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->testClass->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->testClass->getCreatedAt());
    }

    public function testSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->assertEquals($this->testClass, $this->testClass->setUpdatedAt($updatedAt));
    }

    public function testGetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->testClass->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->testClass->getUpdatedAt());
    }
}
