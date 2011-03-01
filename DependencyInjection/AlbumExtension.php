<?php

namespace FOQ\AlbumBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class FOQAlbumExtension extends Extension
{
    public function configLoad($config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('model.xml');
        $loader->load('controller.xml');
        $loader->load('form.xml');
        $loader->load('publisher.xml');
        $loader->load('url_generator.xml');
    }
}
