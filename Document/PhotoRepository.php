<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOQ\AlbumBundle\Model\AlbumInterface;
use MongoId;

class PhotoRepository extends DocumentRepository
{
    public function createQueryByAlbum(AlbumInterface $album)
    {
        return $this->createQueryBuilder()
            ->field('album.$id')->equals(new MongoId($album->getId()))
        ;
    }
}
