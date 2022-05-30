<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @deprecated This Trait is deprecated. Please use the Oro\Bundle\UserBundle\Entity\Ownership\UserAwareTrait instead!
 */
trait UserOwnerTrait
{
    use OrganizationOwnerTrait;

    #[ORM\ManyToOne('Oro\Bundle\UserBundle\Entity\User')]
    #[ORM\JoinColumn('user_owner_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?User $owner;

    public function setOwner(User $owner = null): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }
}
