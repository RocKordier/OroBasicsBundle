<?php
namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

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
        @trigger_error('The method Base::prePersist ist deprecated. Please extend from the AbstractEntity class!', E_USER_DEPRECATED);

        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));

        if (!$this->createdAt) {
            $this->createdAt = $dateTime;
        }

        $this->updatedAt = $dateTime;
    }

    /**
     * Pre update event handler
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        @trigger_error('The method Base::prePersist ist deprecated. Please extend from the AbstractEntity class!', E_USER_DEPRECATED);

        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
