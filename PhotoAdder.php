<?php

namespace FOQ\AlbumBundle;

use FOQ\Model\AlbumInterface;
use FOQ\Model\PhotoInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class PhotoAdder
{
    protected $objectManager;

    public function __construct(DocumentManager $objectManager)
    {
        $this->objectManager = objectManager;
    }

    public function add(AlbumInterface $album, PhotoInterface $photo)
    {
        $photo->setNumber($album->getPhotos()->getNextPhotoNumber());
        $photo->setAlbum($album);
        $album->getPhotos()->add($photo);
    }
}
