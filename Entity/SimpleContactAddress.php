<?php

namespace EHDev\Bundle\BasicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EHDev\Bundle\BasicsBundle\Entity\Traits\LifecycleTrait;
use EHDev\Bundle\BasicsBundle\Model\ExtendSimpleContact;
use EHDev\Bundle\BasicsBundle\Model\ExtendSimpleContactAddress;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * Class SimpleContactAddress
 *
 * @package EHDev\Bundle\BasicsBundle\Entity
 */
/**
 * @ORM\Table("ehdev_basics_sca")
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *       defaultValues={
 *          "entity"={
 *              "icon"="fa-map-marker"
 *          },
 *          "note"={
 *              "immutable"=true
 *          },
 *          "activity"={
 *              "immutable"=true
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "attachment"={
 *              "immutable"=true
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="EHDev\Bundle\BasicsBundle\Entity\Repository\SimpleContactAddressRepository")
 */
class SimpleContactAddress extends ExtendSimpleContactAddress
{
    use LifecycleTrait;

    /**
     * @var \EHDev\Bundle\BasicsBundle\Entity\SimpleContact
     * @ORM\OneToOne(targetEntity="EHDev\Bundle\BasicsBundle\Entity\SimpleContact")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $owner;

    /**
     * @param \EHDev\Bundle\BasicsBundle\Entity\SimpleContact|null $owner
     *
     * @return $this
     */
    public function setOwner(SimpleContact $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return \EHDev\Bundle\BasicsBundle\Entity\SimpleContact
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
