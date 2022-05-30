<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\DependencyInjection;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ehdev_basics');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        SettingsBuilder::append($rootNode, [
            'googlemaps_api_key' => [
                'value' => null,
                'type' => 'text',
            ],
            'bg_username' => [
                'value' => null,
                'type' => 'text',
            ],
        ]);

        return $treeBuilder;
    }
}
