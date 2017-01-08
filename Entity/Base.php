<?php

namespace EHDev\Bundle\BasicsBundle\Entity;

use EHDev\Bundle\BasicsBundle\Entity\Traits\LifecycleTrait;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;

/**
 * Class Base
 *
 * @package EHDev\Bundle\BasicsBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass()
 */
class Base
{
    use LifecycleTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var BusinessUnit
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit")
     * @ORM\JoinColumn(name="business_unit_owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

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