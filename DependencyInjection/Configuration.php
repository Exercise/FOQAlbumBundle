<?php

namespace FOQ\AlbumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('foq_album', 'array');

        $this->addClassSection($rootNode);

        return $treeBuilder->buildTree();
    }

    private function addClassSection(NodeBuilder $node)
    {
        $node
            ->arrayNode('class')
                ->isRequired()
                ->addDefaultsIfNotSet()
                ->arrayNode('model')
                    ->isRequired()
                    ->scalarNode('album')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('photo')->isRequired()->cannotBeEmpty()->end()
                ->end()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->scalarNode('album')->defaultValue('FOQ\\AlbumBundle\\Form\\AlbumForm')->end()
                    ->scalarNode('photo')->defaultValue('FOQ\\AlbumBundle\\Form\\PhotoForm')->end()
                ->end()
                ->arrayNode('controller')
                    ->addDefaultsIfNotSet()
                    ->scalarNode('album')->defaultValue('FOQ\\AlbumBundle\\Controller\\AlbumController')->end()
                    ->scalarNode('photo')->defaultValue('FOQ\\AlbumBundle\\Controller\\PhotoController')->end()
                ->end()
            ->end();
    }
}
