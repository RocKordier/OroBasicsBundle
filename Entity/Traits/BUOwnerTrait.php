<?php

namespace EHDev\Bundle\BasicsBundle\Entity\Traits;

use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * Class BUOwnerTrait
 *
 * @package EHDev\Bundle\BasicsBundle\Entity\Traits
 */
trait BUOwnerTrait
{
    /**
     * @var BusinessUnit
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit")
     * @ORM\JoinColumn(name="business_unit_owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

    /**
     * @return BusinessUnit
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set a business unit owning this report
     *
     * @param BusinessUnit $businessUnit
     *
     * @return self
     */
    public function setOwner(BusinessUnit $businessUnit)
    {
        $this->owner = $businessUnit;

        return $this;
    }

    /**
     * Set organization
     *
     * @param  Organization $organization
     *
     * @return self
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
