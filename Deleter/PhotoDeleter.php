<?php

namespace FOQ\AlbumBundle\Deleter;

use Doctrine\ODM\MongoDB\DocumentManager;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Model\PhotoInterface;

class PhotoDeleter
{
    protected $objectManager;

    public function __construct(DocumentManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function delete(AlbumInterface $album, PhotoInterface $photo)
    {
        $keyToDelete = $album->getPhotos()->indexOf($photo);
        $album->getPhotos()->remove($keyToDelete);
        $this->objectManager->remove($photo);
        $album->updateCount();
    }
}
