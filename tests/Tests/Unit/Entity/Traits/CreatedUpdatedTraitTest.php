<?php
namespace EHDev\BasicsBundle\Tests\Unit\Entity\Traits;

use EHDev\BasicsBundle\Tests\Fixture\CreatedUpdatedTraitTestClass;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass EHDev\BasicsBundle\Entity\Traits\CreatedUpdatedTrait
 */
class CreatedUpdatedTraitTest extends TestCase
{
    private $testClass;

    public function setUp()
    {
        $this->testClass = new CreatedUpdatedTraitTestClass();
    }

    /**
     * @covers ::setCreatedAt
     */
    public function testSetCreatedAt()
    {
        $createdAt = new \DateTime();
        $this->assertEquals($this->testClass, $this->testClass->setCreatedAt($createdAt));
    }

    /**
     * @covers ::getCreatedAt
     */
    public function testGetCreatedAt()
    {
        $createdAt = new \DateTime();
        $this->testClass->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->testClass->getCreatedAt());
    }

    /**
     * @covers ::setUpdatedAt
     */
    public function testSetUpdatedAt()
    {
        $updatedAt = new \DateTime();
        $this->assertEquals($this->testClass, $this->testClass->setUpdatedAt($updatedAt));
    }

    /**
     * @covers ::getUpdatedAt
     */
    public function testGetUpdatedAt()
    {
        $updatedAt = new \DateTime();
        $this->testClass->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->testClass->getUpdatedAt());    }
}
