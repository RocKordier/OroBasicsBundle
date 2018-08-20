<?php
namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;

/**
 * @deprecated This Trait is deprecated. Please use the Oro\Bundle\OrganizationBundle\Entity\Ownership\BusinessUnitAwareTrait instead!
 */
trait BUOwnerTrait
{
    use OrganizationOwnerTrait;

    /**
     * @var BusinessUnit
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit")
     * @ORM\JoinColumn(name="business_unit_owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * @param BusinessUnit $businessUnit
     *
     * @return self
     */
    public function setOwner(BusinessUnit $businessUnit = null)
    {
        $this->owner = $businessUnit;

        return $this;
    }

    /**
     * @return BusinessUnit|null
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
