<?php

namespace FOQ\AlbumBundle;

use Symfony\Component\Routing\Router;
use FOQ\AlbumBundle\Document\Album;
use FOQ\AlbumBundle\Document\Photo;

class UrlGenerator
{
    protected $generator;

    public function __construct(Router $router)
    {
        $this->generator = $router->getGenerator();
    }

    public function getUrlForAlbumShow(Album $album)
    {
        return $this->generator->generate('foq_album_album_show', array(
            'username' => $album->getUser()->getUsername(),
            'slug' => $album->getSlug()
        ));
    }
}
