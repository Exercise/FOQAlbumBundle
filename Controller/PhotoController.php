<?php

namespace FOQ\AlbumBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Document\Photo;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PhotoController extends ContainerAware
{
    public function listByAlbumAction(AlbumInterface $album, $page = 1)
    {
        /**
         * Because subrequests loose the query parameters
         */
        $this->container->get('request')->query->set('page', $page);

        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Photo:byAlbum.html.twig', array(
            'album'  => $album,
            'photos' => $this->getProvider()->getAlbumPhotos($album)
        ));
    }

    public function showAction($username, $slug, $number)
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Photo:show.html.twig', array(
            'album' => $album = $this->getProvider()->getAlbum($username, $slug),
            'photo' => $this->getProvider()->getPhoto($album, $number, true)
        ));
    }

    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    protected function getProvider()
    {
        return $this->container->get('foq_album.provider.photo');
    }
}
