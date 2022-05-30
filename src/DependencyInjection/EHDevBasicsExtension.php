<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EHDevBasicsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('form.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->prependExtensionConfig($this->getAlias(), array_intersect_key($config, array_flip(['settings'])));
    }

    public function getAlias(): string
    {
        return 'ehdev_basics';
    }
}
