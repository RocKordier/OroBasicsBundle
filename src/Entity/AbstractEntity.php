<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EHDev\BasicsBundle\Entity\Traits\LifecycleTrait;

#[ORM\MappedSuperclass]
abstract class AbstractEntity
{
    use LifecycleTrait;
}
