<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait LifecycleTrait
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\PrePersist
     */
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        @trigger_error('The method Base::prePersist ist deprecated. Please extend from the AbstractEntity class!', E_USER_DEPRECATED);

        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->createdAt = $dateTime;
        $this->updatedAt = $dateTime;
    }

    /**
     * @ORM\PreUpdate
     */
    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        @trigger_error('The method Base::prePersist ist deprecated. Please extend from the AbstractEntity class!', E_USER_DEPRECATED);

        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
