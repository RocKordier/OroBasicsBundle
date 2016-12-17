<?php

namespace EHDev\Bundles\Oro\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LifecycleTrait
 *
 * @package EHDev\Bundles\Oro\BasicsBundle\Entity\Traits
 */
trait LifecycleTrait
{
    use CreatedUpdatedTrait;

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Pre update event handler
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}