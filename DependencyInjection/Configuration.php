<?php

namespace FOQ\AlbumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $treeBuilder->root('foq_album', 'array')->children()
            ->arrayNode('class')
                ->isRequired()
                ->children()
                    ->arrayNode('model')
                        ->isRequired()
                        ->children()
                            ->scalarNode('album')->isRequired()->end()
                            ->scalarNode('photo')->isRequired()->end()
                        ->end()
                    ->end()
                    ->arrayNode('form')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('album')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Form\\AlbumForm')->end()
                            ->scalarNode('photo')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Form\\PhotoForm')->end()
                        ->end()
                    ->end()
                    ->arrayNode('controller')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('album')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Controller\\AlbumController')->end()
                            ->scalarNode('photo')->cannotBeEmpty()->defaultValue('FOQ\\AlbumBundle\\Controller\\PhotoController')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('service')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('publisher')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('album')->cannotBeEmpty()->defaultValue('foq_album.publisher.album.default')->end()
                        ->end()
                    ->end()
                    ->arrayNode('adder')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('photo')->cannotBeEmpty()->defaultValue('foq_album.adder.photo.default')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('pagination')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('item_count_per_page')->defaultValue(10)->end()
                    ->scalarNode('page_range')->defaultValue(5)->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder->buildTree();
    }
}
