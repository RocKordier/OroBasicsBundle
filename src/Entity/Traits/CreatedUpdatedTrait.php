<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

trait CreatedUpdatedTrait
{
    /**
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.created_at"
     *          },
     *      }
     * )
     */
    #[ORM\Column('created_at', type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    protected \DateTime $createdAt;

    /**
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.updated_at"
     *          },
     *      }
     * )
     */
    #[ORM\Column('updated_at', type: 'datetime')]
    #[Gedmo\Timestampable(on: 'update')]
    protected \DateTime $updatedAt;

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
