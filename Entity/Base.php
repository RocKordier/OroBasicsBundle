<?php

namespace EHDev\Bundle\BasicsBundle\Entity;

use EHDev\Bundle\BasicsBundle\Entity\Traits\LifecycleTrait;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
