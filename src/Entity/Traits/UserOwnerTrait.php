<?php
namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @deprecated This Trait is deprecated. Please use the Oro\Bundle\UserBundle\Entity\Ownership\UserAwareTrait instead!
 */
trait UserOwnerTrait
{
    use OrganizationOwnerTrait;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * @param User $owner
     *
     * @return self
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
