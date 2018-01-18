<?php
namespace EHDev\BasicsBundle\Tests\Unit\Entity;

use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use PHPUnit\Framework\TestCase;
use EHDev\BasicsBundle\Tests\Fixture\Testclass;

/**
 * @coversDefaultClass EHDev\BasicsBundle\Entity\Base
 */
class BaseTest extends TestCase
{
    /** @var Testclass */
    private $testClass;

    public function setUp()
    {
        $this->testClass = new Testclass();
    }

    /**
     * @covers ::getId
     * @covers ::getCreatedAt
     * @covers ::getUpdatedAt
     * @covers ::getOrganization
     * @covers ::getOwner
     */
    public function testFieldsAreNull()
    {
        $this->assertNull($this->testClass->getId());
        $this->assertNull($this->testClass->getCreatedAt());
        $this->assertNull($this->testClass->getUpdatedAt());
        $this->assertNull($this->testClass->getOrganization());
        $this->assertNull($this->testClass->getOwner());
    }

    /**
     * @covers ::setCreatedAt
     * @covers ::setUpdatedAt
     * @covers ::getCreatedAt
     * @covers ::getUpdatedAt
     */
    public function testFieldsWithValues()
    {
        $createdDate = new \DateTime('2018-01-01Z00:00:01');
        $updatedDate = new \DateTime('2018-01-02Z00:00:02');

        $this->testClass->setCreatedAt($createdDate);
        $this->testClass->setUpdatedAt($updatedDate);

        $this->assertEquals($createdDate, $this->testClass->getCreatedAt());
        $this->assertEquals($updatedDate, $this->testClass->getUpdatedAt());
    }

    /**
     * @covers ::prePersist
     * @covers ::preUpdate
     */
    public function testLifecycle()
    {
        $this->testClass->prePersist();
        $this->testClass->preUpdate();

        $this->assertInstanceof(\DateTime::class, $this->testClass->getUpdatedAt());
        $this->assertInstanceof(\DateTime::class, $this->testClass->getCreatedAt());
    }

    /**
     * @covers ::setOrganization
     * @covers ::BusinessUnit
     * @covers ::getOrganization
     * @covers ::getOwner
     */
    public function testBUOwnerTrait()
    {
        $testOrganization = new Organization();
        $testOwner = new BusinessUnit();

        $this->testClass->setOrganization($testOrganization);
        $this->testClass->setOwner($testOwner);

        $this->assertEquals('', $this->testClass->getOrganization());
        $this->assertEquals('', $this->testClass->getOwner());
    }
}
