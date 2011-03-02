<?php

namespace FOQ\AlbumBundle\Twig;

use FOQ\AlbumBundle\UrlGenerator;
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
            'foq_album_urlGenerator'         => new Twig_Function_Method($this, 'getUrlGenerator'),
        );
    }

    /**
     * Returns the url generator
     *
     * @return UrlGenerator
     */
    public function getUrlGenerator()
    {
        return $this->urlGenerator;
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
