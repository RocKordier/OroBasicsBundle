<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * @deprecated This Trait is deprecated. Please use the Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait instead!
 */
trait OrganizationOwnerTrait
{
    #[ORM\ManyToOne('Oro\Bundle\OrganizationBundle\Entity\Organization')]
    #[ORM\JoinColumn('organization_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?Organization $organization;

    public function setOrganization(Organization $organization = null): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }
}
