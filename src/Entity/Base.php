<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EHDev\BasicsBundle\Entity\Traits\CreatedUpdatedTrait;

#[ORM\HasLifecycleCallbacks]
#[ORM\MappedSuperclass]
class Base
{
    use CreatedUpdatedTrait;

    #[ORM\Column('id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
