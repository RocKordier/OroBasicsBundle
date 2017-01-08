<?php

namespace EHDev\Bundle\BasicsBundle\Entity\Traits;

use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;

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
     * @return \Extend\Entity\EX_OroOrganizationBundle_BusinessUnit|\Oro\Bundle\OrganizationBundle\Entity\BusinessUnit
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
     * @return $this
     */
    public function setOwner(BusinessUnit $businessUnit)
    {
        $this->owner = $businessUnit;

        return $this;
    }
}
