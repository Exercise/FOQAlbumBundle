<?php

namespace FOQ\AlbumBundle;

use Symfony\Component\Routing\Router;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Document\Photo;

class UrlGenerator
{
    protected $generator;

    public function __construct(Router $router)
    {
        $this->generator = $router->getGenerator();
    }

    public function getAlbumUrl($route, AlbumInterface $album, $absolute = false)
    {
        return $this->generator->generate($route, array(
            'username' => $album->getUser()->getUsername(),
            'slug'     => $album->getSlug()
        ), $absolute);
    }
}
