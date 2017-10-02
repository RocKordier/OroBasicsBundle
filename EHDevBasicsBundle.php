<?php
namespace EHDev\Bundle\BasicsBundle;

use EHDev\Bundle\BasicsBundle\DependencyInjection\EHDevBasicsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EHDevBasicsBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EHDevBasicsExtension();
    }
}
