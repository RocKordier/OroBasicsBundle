<?php
namespace EHDev\BasicsBundle\DependencyInjection\Compiler;

use EHDev\BasicsBundle\Model\Action\StripHtmlTags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OverrideStripHtmlTagsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $stripHtmlTags = $container->getDefinition('oro_email.workflow.action.strip_html_tags');
        $stripHtmlTags->setClass(StripHtmlTags::class);
        $stripHtmlTags->addArgument(new Reference('oro_config.manager'));
    }
}
