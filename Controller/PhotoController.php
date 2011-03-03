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
    public function listByAlbumAction(AlbumInterface $album)
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Photo:byAlbum.html.twig', array(
            'album'  => $album,
            'photos' => $this->getProvider()->getAlbumPhotos($album)
        ));
    }

    public function showAction($username, $slug, $number)
    {
        $album = $this->get('foq_album.repository.album')->findOneBySlug($albumSlug);
        if(!$album) {
            throw new NotFoundHttpException(sprintf('Album "%s" does not exist', $albumSlug));
        }
        $photo = $album->getPhotoByKey($key);
        if(!$photo) {
            throw new NotFoundHttpException(sprintf('Photo "%s" does not exist in album "%s"', $key, $albumSlug));
        }
        $this->addAlbumBreadCrumb($album);
        $viewKey = implode('|', array('AlbumPhoto', $album->getId(), $photo->getKey()));
        if(!$this->get('session')->has($viewKey)) {
            $photo->incrementImpressions();
            $this->get('session')->set($viewKey, true);
            $this->getOdm()->flush();
        }

        return $this->render('AlbumBundle:Frontend:photoView.twig', array(
            'album' => $album,
            'photo' => $photo
        ));
    }

    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    protected function getProvider()
    {
        return $this->container->get('foq_album.provider');
    }
}
