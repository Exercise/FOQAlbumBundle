<?php

namespace FOQ\AlbumBundle\Controller;

use FOQ\CommonBundle\Controller\Controller as ExerciseController;
use FOQ\AlbumBundle\Document\Album;
use FOQ\AlbumBundle\Document\Photo;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PhotoFrontendController extends ExerciseController
{
    public function viewAction($albumSlug, $key)
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

    public function listByAlbumAction(Album $album)
    {
        $page = $this->get('request')->get('page', 1);
        $numPerPage = 5;
        $adapter = new ArrayAdapter($album->getPhotos()->toArray());
        $pager = new Paginator($adapter);
        $pager->setCurrentPageNumber($page);
        $pager->setItemCountPerPage($numPerPage);
        $pager->setPageRange(5);
        $this->addAlbumBreadCrumb($album);

        return $this->render('AlbumBundle:Frontend:photoListByAlbum.twig', array(
            'album' => $album,
            'photos' => $pager
        ));
    }

    protected function addAlbumBreadCrumb(Album $album)
    {
        $this->get('menu.crumb')->addChild('Albums', $this->generateUrl('album_index'));
        $this->get('menu.crumb')->addChild($album->getTitle(), $this->generateUrl('album_view', array('slug' => $album->getSlug())));
    }
}
