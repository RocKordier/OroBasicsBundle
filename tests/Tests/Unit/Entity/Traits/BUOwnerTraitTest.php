<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Entity\Traits;

use EHDev\BasicsBundle\Tests\Fixture\BUOwnerTraitTestClass;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EHDev\BasicsBundle\Entity\Traits\BUOwnerTrait
 */
class BUOwnerTraitTest extends TestCase
{
    private BUOwnerTraitTestClass $testClass;

    public function setUp(): void
    {
        $this->testClass = new BUOwnerTraitTestClass();
    }

    /**
     * @covers ::setOwner
     */
    public function testSetOwner(): void
    {
        $bu = new BusinessUnit();
        $this->assertEquals($this->testClass, $this->testClass->setOwner($bu));
    }

    /**
     * @covers ::getOwner
     */
    public function testGetOwner(): void
    {
        $bu = new BusinessUnit();
        $this->testClass->setOwner($bu);
        $this->assertEquals($bu, $this->testClass->getOwner());
    }

    /**
     * @covers ::setOrganization
     */
    public function testSetOrganization(): void
    {
        $org = new Organization();
        $this->assertEquals($this->testClass, $this->testClass->setOrganization($org));
    }

    /**
     * @covers ::getOrganization
     */
    public function testGetOrganization(): void
    {
        $org = new Organization();
        $this->testClass->setOrganization($org);
        $this->assertEquals($org, $this->testClass->getOrganization());
    }
}
