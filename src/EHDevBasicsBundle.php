<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle;

use EHDev\BasicsBundle\DependencyInjection\Compiler\AutowireAliasPass;
use EHDev\BasicsBundle\DependencyInjection\EHDevBasicsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EHDevBasicsBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EHDevBasicsExtension();
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AutowireAliasPass());
    }
}
