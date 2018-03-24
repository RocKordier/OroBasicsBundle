<?php
namespace EHDev\BasicsBundle;

use EHDev\BasicsBundle\DependencyInjection\Compiler\OverrideStripHtmlTagsCompilerPass;
use EHDev\BasicsBundle\DependencyInjection\EHDevBasicsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EHDevBasicsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideStripHtmlTagsCompilerPass());
    }

    public function getContainerExtension()
    {
        return new EHDevBasicsExtension();
    }
}
