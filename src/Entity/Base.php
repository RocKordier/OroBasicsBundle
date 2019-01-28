<?php
namespace EHDev\BasicsBundle\Entity;

use EHDev\BasicsBundle\Entity\Traits\LifecycleTrait;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass()
 */
class Base
{
    use LifecycleTrait;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}
