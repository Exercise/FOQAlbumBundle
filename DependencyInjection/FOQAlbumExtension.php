<?php

namespace FOQ\AlbumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class FOQAlbumExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('model.xml');
        $loader->load('controller.xml');
        $loader->load('form.xml');
        $loader->load('publisher.xml');
        $loader->load('url_generator.xml');
        $loader->load('provider.xml');
        $loader->load('adder.xml');
        $loader->load('sorter.xml');
        $loader->load('deleter.xml');
        $loader->load('twig.xml');
        $loader->load('security.xml');
        $loader->load('uploader.xml');
        $loader->load('validator.xml');

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        $this->remapParametersNamespaces($config['class'], $container, array(
            'model'      => 'foq_album.model.%s.class',
            'controller' => 'foq_album.controller.%s.class',
        ));

        $container->setAlias('foq_album.publisher.album', $config['service']['publisher']['album']);
        $container->setAlias('foq_album.adder.photo', $config['service']['adder']['photo']);
        $container->setAlias('foq_album.uploader', $config['service']['uploader']);
        $container->setAlias('foq_album.validator.image', $config['service']['validator']['image']);
        $container->setAlias('foq_album.constraint_factory.image', $config['service']['constraint_factory']['image']);
        $container->setAlias('foq_album.form_type.album', $config['service']['form_type']['album']);
        $container->setAlias('foq_album.form_handler.album', $config['service']['form_handler']['album']);
        $container->setAlias('foq_album.provider.album', $config['service']['provider']['album']);
        $container->setAlias('foq_album.provider.photo', $config['service']['provider']['photo']);

        foreach (array('album', 'photo') as $type) {
            foreach (array('item_count_per_page', 'page_range') as $key) {
                $container->setParameter('foq_album.provider.' . $type . '.' . $key, $config['pagination'][$type][$key]);
            }
        }
    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (isset($config[$name])) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!isset($config[$ns])) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    if (null !== $value) {
                        $container->setParameter(sprintf($map, $name), $value);
                    }
                }
            }
        }
    }
}
