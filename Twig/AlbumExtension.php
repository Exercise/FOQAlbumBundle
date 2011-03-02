<?php

namespace FOQ\AlbumBundle\Twig;

use FOQ\AlbumBundle\UrlGenerator;
use FOQ\AlbumBundle\Model\AlbumInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Twig_Function_Method;
use Twig_Extension;

class AlbumExtension extends Twig_Extension
{
    protected $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'foq_album_albumUrl'         => new Twig_Function_Method($this, 'getAlbumUrl'),
        );
    }

    /**
     * Returns the url generator
     *
     * @return UrlGenerator
     */
    public function getAlbumUrl($route, AlbumInterface $album, $absolute = false)
    {
        return $this->urlGenerator->getAlbumUrl($route, $album, $absolute);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'foq_album';
    }
}
