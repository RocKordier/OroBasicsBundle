<?php
namespace EHDev\BasicsBundle\Tests\Fixture;

use EHDev\BasicsBundle\Entity\Base;
use EHDev\BasicsBundle\Entity\Traits\BUOwnerTrait;

class Testclass extends Base
{
    use BUOwnerTrait;
}
