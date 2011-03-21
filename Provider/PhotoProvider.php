<?php

namespace FOQ\AlbumBundle\Provider;

use FOS\UserBundle\Model\UserManagerInterface;
use FOQ\AlbumBundle\Document\PhotoRepository;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Sorter\PhotoSorter;
use FOQ\AlbumBundle\SecurityHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Paginator\Paginator;
use ZendPaginatorAdapter\DoctrineMongoDBAdapter;
use Zend\Paginator\Adapter\ArrayAdapter;

/**
 * High level object finder that uses the route parameters as method arguments
 */
class PhotoProvider extends AbstractProvider
{
    protected $photoRepository;
    protected $userManager;
    protected $securityHelper;
    protected $documentManager;
    protected $photoSorter;
    protected $request;

    public function __construct(PhotoRepository $photoRepository, UserManagerInterface $userManager, SecurityHelper $securityHelper, DocumentManager $documentManager, PhotoSorter $photoSorter, Request $request = null)
    {
        $this->photoRepository = $photoRepository;
        $this->userManager     = $userManager;
        $this->securityHelper  = $securityHelper;
        $this->documentManager = $documentManager;
        $this->photoSorter     = $photoSorter;
        $this->request         = $request;
    }

    /**
     * Get a paginator of photos of an album
     *
     * @return Paginator
     **/
    public function getAlbumPhotos(AlbumInterface $album, $page)
    {
        return $this->paginate($this->photoRepository->createQueryByAlbum($album, $this->photoSorter->getDatabaseOrder()), $page);
    }

    /**
     * Find a photo
     *
     * @return Photo
     **/
    public function getPhoto(AlbumInterface $album, $number, $incrementImpressions = false)
    {
        $photo = $album->getPhotos()->getPhotoByNumber($number);

        if (empty($photo)) {
            throw new NotFoundHttpException(sprintf('The photo number "%s" does not exist in album "%s"', $number, $album->getTitle()));
        }

        if ($incrementImpressions) {
            $this->incrementImpressions($photo);
        }

        return $photo;

    }

    /**
     * Returns a new photo instance
     *
     * @return PhotoInterface
     **/
    public function createPhoto()
    {
        return $this->photoRepository->createNewPhoto();
    }
}
