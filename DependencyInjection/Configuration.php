<?php
namespace EHDev\Bundle\BasicsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('ehdev_basics');

        SettingsBuilder::append($rootNode, [
            'googlemaps_api_key'     => [
                'value' => null,
                'type'  => 'text',
            ],
        ]);

        return $treeBuilder;
    }
}
