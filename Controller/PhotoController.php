<?php

namespace FOQ\AlbumBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        return $this->getTemplating()->renderResponse('FOQAlbum:Photo:byAlbum.html.twig', array(
            'album'  => $album,
            'photos' => $this->getPhotoProvider()->getAlbumPhotos($album)
        ));
    }

    public function showAction($username, $slug, $number)
    {
        return $this->getTemplating()->renderResponse('FOQAlbum:Photo:show.html.twig', array(
            'album' => $album = $this->getAlbumProvider()->getAlbum($username, $slug),
            'photo' => $this->getPhotoProvider()->getPhoto($album, $number, true)
        ));
    }

    public function deleteAction($username, $slug, $number)
    {
        $album = $this->getAlbumProvider()->getAlbum($username, $slug);
        $this->checkAlbumOwning($album);
        $photo = $this->getPhotoProvider()->getPhoto($album, $number);
        $this->container->get('foq_album.deleter.photo')->delete($album, $photo);
        $this->container->get('foq_album.object_manager')->flush();

        return new RedirectResponse($this->container->get('router')->generate('foq_album_index'));
    }

    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    protected function getAlbumProvider()
    {
        return $this->container->get('foq_album.provider.album');
    }

    protected function getPhotoProvider()
    {
        return $this->container->get('foq_album.provider.photo');
    }

    protected function checkAlbumOwning(AlbumInterface $album)
    {
        if ($album->getUser() !== $this->container->get('foq_album.security_helper')->getUser()) {
            throw new AccessDeniedException();
        }
    }

}
