<?php

namespace FOQ\AlbumBundle;

use Symfony\Component\Routing\Router;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Model\PhotoInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlGenerator
{
    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getAlbumUrl($route, AlbumInterface $album, array $parameters = array(), $absolute = false)
    {
        return $this->urlGenerator->generate($route, array_merge($parameters, array(
            'username' => $album->getUser()->getUsernameCanonical(),
            'slug'     => $album->getSlug()
        )), $absolute);
    }

    public function getPhotoUrl($route, PhotoInterface $photo, array $parameters = array(), $absolute = false)
    {
        return $this->urlGenerator->generate($route, array_merge($parameters, array(
            'username' => $photo->getAlbum()->getUser()->getUsernameCanonical(),
            'slug'     => $photo->getAlbum()->getSlug(),
            'number'   => $photo->getNumber()
        )), $absolute);
    }
}
