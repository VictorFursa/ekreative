<?php

namespace RedmineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redmine');

        $rootNode
            ->children()
                ->scalarNode('url')->isRequired()->end()
                ->scalarNode('api_key')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
