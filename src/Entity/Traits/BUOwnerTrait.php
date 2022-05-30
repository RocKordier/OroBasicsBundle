<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;

/**
 * @deprecated This Trait is deprecated. Please use the Oro\Bundle\OrganizationBundle\Entity\Ownership\BusinessUnitAwareTrait instead!
 */
trait BUOwnerTrait
{
    use OrganizationOwnerTrait;

    #[ORM\ManyToOne('Oro\Bundle\OrganizationBundle\Entity\BusinessUnit')]
    #[ORM\JoinColumn('business_unit_owner_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?BusinessUnit $owner;

    public function setOwner(?BusinessUnit $businessUnit = null): self
    {
        $this->owner = $businessUnit;

        return $this;
    }

    public function getOwner(): ?BusinessUnit
    {
        return $this->owner;
    }
}
