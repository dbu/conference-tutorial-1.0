<?php

namespace Dbu\ConferenceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dbu_conference');

        $rootNode
            ->children()
                ->scalarNode('home_path')->defaultValue('/cms/simple')->end()
                ->scalarNode('speakers_path')->defaultValue('speakers')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
