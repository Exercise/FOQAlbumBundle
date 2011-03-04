<?php

namespace FOQ\AlbumBundle\Deleter;

use Doctrine\ODM\MongoDB\DocumentManager;
use FOQ\AlbumBundle\Model\AlbumInterface;

class AlbumDeleter
{
    protected $objectManager;

    public function __construct(DocumentManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function delete(AlbumInterface $album)
    {
        $this->objectManager->remove($album);

        foreach($album->getPhotos() as $photo) {
            $this->objectManager->remove($photo);
        }
    }
}
