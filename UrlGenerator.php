<?php

namespace FOQ\AlbumBundle;

use Symfony\Component\Routing\Router;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Model\PhotoInterface;

class UrlGenerator
{
    protected $generator;

    public function __construct(Router $router)
    {
        $this->generator = $router->getGenerator();
    }

    public function getAlbumUrl($route, AlbumInterface $album, array $parameters = array(), $absolute = false)
    {
        return $this->generator->generate($route, array_merge($parameters, array(
            'username' => $album->getUser()->getUsernameCanonical(),
            'slug'     => $album->getSlug()
        )), $absolute);
    }

    public function getPhotoUrl($route, PhotoInterface $photo, array $parameters = array(), $absolute = false)
    {
        return $this->generator->generate($route, array_merge($parameters, array(
            'username' => $photo->getAlbum()->getUser()->getUsernameCanonical(),
            'slug'     => $photo->getAlbum()->getSlug(),
            'number'   => $photo->getNumber()
        )), $absolute);
    }
}
