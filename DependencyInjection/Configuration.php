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
                ->arrayNode('model')
                    ->isRequired()
                    ->scalarNode('album')->isRequired()->end()
                    ->scalarNode('photo')->isRequired()->end()
                ->end()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->scalarNode('album')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Form\\AlbumForm')->end()
                    ->scalarNode('photo')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Form\\PhotoForm')->end()
                ->end()
                ->arrayNode('controller')
                    ->addDefaultsIfNotSet()
                    ->scalarNode('album')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Controller\\AlbumController')->end()
                    ->scalarNode('photo')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Controller\\PhotoController')->end()
                ->end()
            ->end()
            ->arrayNode('service')
                ->addDefaultsIfNotSet()
                ->arrayNode('publisher')
                    ->addDefaultsIfNotSet()
                    ->scalarNode('album')->cannotBeEmpty()->defaultValue('foq_album.publisher.album.default')->end()
                ->end()
                ->arrayNode('adder')
                    ->addDefaultsIfNotSet()
                    ->scalarNode('photo')->cannotBeEmpty()->defaultValue('foq_album.adder.photo.default')->end()
                ->end()
            ->end();
    }
}
