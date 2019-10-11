<?php
namespace EHDev\BasicsBundle\DependencyInjection;

use Oro\Bundle\FormBundle\Model\UpdateHandlerFacade;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EHDevBasicsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('form.yml');

        if (method_exists(Definition::class, 'setAutowired')) {
            $loader->load('autowire.yml');
        }

        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->prependExtensionConfig($this->getAlias(), array_intersect_key($config, array_flip(['settings'])));

        $container->addAliases([
            UpdateHandlerFacade::class => 'oro_form.update_handler',
        ]);
    }

    public function getAlias(): string
    {
        return 'ehdev_basics';
    }
}
