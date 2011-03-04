<?php

namespace FOQ\AlbumBundle\Adder;

use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Model\PhotoInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class PhotoAdder
{
    protected $objectManager;

    public function __construct(DocumentManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function add(AlbumInterface $album, PhotoInterface $photo)
    {
        $photo->setNumber($album->getPhotos()->getNextPhotoNumber());
        $photo->setAlbum($album);
        $album->getPhotos()->add($photo);
        $album->updateCount();
        $album->setUpdatedNow();

        $this->objectManager->persist($photo);
    }
}
